<?php
// api/update_progress.php
header('Content-Type: application/json');
include "../../includes/config.php"; // $conn


try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $student = $_SESSION['userid'] ?? null;
    if (!$student) throw new Exception("Unauthorized");

    $cm_id = isset($_POST['cm_id']) ? (int)$_POST['cm_id'] : 0;
    $chapter_id = isset($_POST['chapter_id']) ? (int)$_POST['chapter_id'] : 0;
    $launch_course_id = $_POST['launch_course_id'] ?? null;
    $phase = $_POST['phase'] ?? ''; // expected: 'material'|'video'|'quiz'
    $learning_mode = $_POST['learning_mode'] ?? 'flexible'; // 'sequential' or 'flexible'

    if (!$cm_id || !$chapter_id || !in_array($phase, ['material','video','quiz'], true)) {
        throw new Exception("Missing/invalid parameters");
    }

    // Phase column name mapping
    $phaseColMap = [
        'material' => 'phase_material',
        'video'    => 'phase_video',
        'quiz'     => 'phase_quiz'
    ];
    $phaseCol = $phaseColMap[$phase];

    // Decide weights (customize if needed)
    $weights = ['material'=>1, 'video'=>1, 'quiz'=>1]; // equal
    $totalWeight = array_sum($weights);

    // Begin transaction
    $conn->begin_transaction();

    // Upsert student_chapter_progress row
    $sqlUpsert = "
        INSERT INTO student_chapter_progress
        (student_reg_no, cm_id, launch_course_id, chapter_id, {$phaseCol}, started_at, unlocked, chapter_percent, updated_at)
        VALUES (?, ?, ?, ?, 1, NOW(), 1, 0, NOW())
        ON DUPLICATE KEY UPDATE {$phaseCol} = 1, updated_at = NOW()
    ";
    $stmt = $conn->prepare($sqlUpsert);
    $stmt->bind_param("siis", $student, $cm_id, $launch_course_id, $chapter_id);
    if (!$stmt->execute()) throw new Exception("Upsert failed: " . $stmt->error);
    $stmt->close();

    // Recalculate chapter_percent
    $stmt = $conn->prepare("
        SELECT phase_material, phase_video, phase_quiz
        FROM student_chapter_progress
        WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?
    ");
    $stmt->bind_param("sis", $student, $cm_id, $chapter_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    $score = 0;
    foreach ($weights as $p => $w) {
        $col = 'phase_' . $p;
        $val = isset($row[$col]) ? (int)$row[$col] : 0;
        $score += $val * $w;
    }
    $chapterPercent = (int) round(($score / $totalWeight) * 100); // 0-100

    // Update chapter_percent
    $stmt = $conn->prepare("
        UPDATE student_chapter_progress
        SET chapter_percent = ?, updated_at = NOW()
        WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?
    ");
    $stmt->bind_param("isis", $chapterPercent, $student, $cm_id, $chapter_id);
    if (!$stmt->execute()) throw new Exception("Failed to update chapter_percent: " . $stmt->error);
    $stmt->close();

    // If sequential and chapter reached 100%, unlock next chapter (by ordering modules' chapter_no)
    if ($learning_mode === 'sequential' && $chapterPercent >= 100) {
        // find next chapter id (assume module table has an ordering column like chapter_no)
        $stmt = $conn->prepare("
            SELECT chapter_id FROM module
            WHERE cm_id = ? AND chapter_id > ?
            ORDER BY chapter_id ASC LIMIT 1
        ");
        // NOTE: If your module table uses 'chapter_no' for order, adjust WHERE/ORDER accordingly.
        $stmt->bind_param("ii", $cm_id, $chapter_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $next = $result->fetch_assoc();
        $stmt->close();

        if ($next && isset($next['chapter_id'])) {
            $nextId = (int)$next['chapter_id'];
            // Upsert unlocked for next chapter, but don't touch its phases
            $stmt = $conn->prepare("
                INSERT INTO student_chapter_progress (student_reg_no, cm_id, launch_course_id, chapter_id, unlocked, started_at)
                VALUES (?, ?, ?, ?, 1, NULL)
                ON DUPLICATE KEY UPDATE unlocked = 1
            ");
            $stmt->bind_param("siis", $student, $cm_id, $launch_course_id, $nextId);
            if (!$stmt->execute()) throw new Exception("Failed to unlock next chapter: " . $stmt->error);
            $stmt->close();
        }
    }

    // Recalculate course_percent (average of chapter_percent for all chapters in module)
    // Count chapters in module
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM module WHERE cm_id = ?");
    $stmt->bind_param("i", $cm_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $totalCh = (int)$res->fetch_assoc()['total'];
    $stmt->close();

    if ($totalCh === 0) {
        $coursePercent = $chapterPercent; // fallback
    } else {
        // Sum chapter_percent for student's chapters in this cm_id
        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(chapter_percent),0) as sumperc 
            FROM student_chapter_progress
            WHERE student_reg_no = ? AND cm_id = ?
        ");
        $stmt->bind_param("si", $student, $cm_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $sumPerc = (int)$res->fetch_assoc()['sumperc'];
        $stmt->close();

        // For chapters without a student_chapter_progress row assume 0
        $coursePercent = (int) round($sumPerc / max(1, $totalCh));
    }

    // Upsert student_course_progress
    $stmt = $conn->prepare("
        INSERT INTO student_course_progress (student_reg_no, cm_id, launch_course_id, course_percent, course_started_at, updated_at)
        VALUES (?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE course_percent = ?, updated_at = NOW()
    ");
    $stmt->bind_param("siisii", $student, $cm_id, $launch_course_id, $coursePercent, $coursePercent, $coursePercent);
    // The above bind is messy due to PHP typed binding; safer to separate:
    $stmt->close();
    $stmt = $conn->prepare("
        INSERT INTO student_course_progress (student_reg_no, cm_id, launch_course_id, course_percent, course_started_at, updated_at)
        VALUES (?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE course_percent = ?, updated_at = NOW()
    ");
    $stmt->bind_param("siisii", $student, $cm_id, $launch_course_id, $coursePercent, $coursePercent, $coursePercent);
    // Because bind types above are incorrect, replace with this working version:
    $stmt->close();

    // Proper upsert execute:
    $stmt = $conn->prepare("
        INSERT INTO student_course_progress (student_reg_no, cm_id, launch_course_id, course_percent, course_started_at, updated_at)
        VALUES (?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE course_percent = VALUES(course_percent), updated_at = NOW()
    ");
    $stmt->bind_param("siis", $student, $cm_id, $launch_course_id, $coursePercent);
    if (!$stmt->execute()) throw new Exception("Failed to upsert course progress: " . $stmt->error);
    $stmt->close();

    $conn->commit();

    // Return fresh progress info for client convenience
    echo json_encode([
        'success' => true,
        'chapter_percent' => $chapterPercent,
        'course_percent' => $coursePercent,
        'message' => 'Progress updated'
    ]);
    exit;

} catch (Exception $e) {
    if ($conn && $conn->errno) $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
