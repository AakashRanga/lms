<?php
header('Content-Type: application/json; charset=utf-8');
include "../../includes/config.php";

try {
    // Read input (JSON body preferred, fall back to POST)
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    // Required fields
    $student_reg_no = isset($input['student_reg_no']) ? trim($input['student_reg_no']) : null;
    $cm_id = isset($input['cm_id']) ? intval($input['cm_id']) : null;
    $chapter_id = isset($input['chapter_id']) ? intval($input['chapter_id']) : null;
    $phase_type = isset($input['phase_type']) ? strtolower(trim($input['phase_type'])) : null;
    // progress is 0..100 (integer)
    $progress = isset($input['progress']) ? intval($input['progress']) : null;

    // Config / thresholds
    $quiz_pass_threshold = 50; // percent required to consider quiz "passed" (you can change)

    // Validate
    if (!$student_reg_no || !$cm_id || !$chapter_id || !$phase_type) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'Missing required parameters. Required: student_reg_no, cm_id, chapter_id, phase_type']);
        exit;
    }
    $allowed_phases = ['material', 'video', 'quiz'];
    if (!in_array($phase_type, $allowed_phases)) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'phase_type must be one of: material, video, quiz']);
        exit;
    }
    if ($progress === null) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'progress (0-100) is required']);
        exit;
    }
    if ($progress < 0) $progress = 0;
    if ($progress > 100) $progress = 100;

    // Start transaction
    $conn->begin_transaction();

    // Ensure there's an entry for this student + chapter in student_chapter_progress
    $sqlCheck = "SELECT * FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ? FOR UPDATE";
    $stmt = $conn->prepare($sqlCheck);
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param('sii', $student_reg_no, $cm_id, $chapter_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        // existing row
        $phase_material = intval($row['phase_material']);
        $phase_video = intval($row['phase_video']);
        $phase_quiz = intval($row['phase_quiz']);
        $unlocked = intval($row['unlocked']);
    } else {
        // Insert default row (locked by default; unlocked can be 1 for first chapter but we leave control to your logic)
        $insSql = "INSERT INTO student_chapter_progress (student_reg_no, cm_id, launch_course_id, chapter_id, phase_material, phase_video, phase_quiz, chapter_percent, unlocked, started_at, updated_at)
                   VALUES (?, ?, NULL, ?, 0, 0, 0, 0, 0, NULL, NOW())";
        $ins = $conn->prepare($insSql);
        if (!$ins) throw new Exception("Prepare failed (insert): " . $conn->error);
        $ins->bind_param('sis', $student_reg_no, $cm_id, $chapter_id);
        if (!$ins->execute()) throw new Exception("Insert failed: " . $ins->error);
        // re-select the row
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $phase_material = 0; $phase_video = 0; $phase_quiz = 0; $unlocked = 0;
    }
    $stmt->close();

    // Determine column name and new value
    $colName = null;
    if ($phase_type === 'material') $colName = 'phase_material';
    if ($phase_type === 'video')    $colName = 'phase_video';
    if ($phase_type === 'quiz')     $colName = 'phase_quiz';

    // Update the phase percent if new progress is greater than old (or always update).
    // Here we will set to max(old, new) to avoid regressions (but you can override)
    $oldValue = intval($row[$colName] ?? 0);
    $newValue = max($oldValue, $progress);

    // Update row
    $updateSql = "UPDATE student_chapter_progress
                  SET {$colName} = ?, chapter_percent = ROUND((phase_material*0.25 + phase_video*0.25 + phase_quiz*0.5)), updated_at = NOW()
                  WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?";
    $upd = $conn->prepare($updateSql);
    if (!$upd) throw new Exception("Prepare failed (update): " . $conn->error);
    $upd->bind_param('isii', $newValue, $student_reg_no, $cm_id, $chapter_id);
    // Note: bind_param format has one too many types; fix properly below by using dynamic binding:
    // We'll use a safer approach—build param types correctly.

    // Rebuild update with proper binding
    $upd->close();
    $updateSql = "UPDATE student_chapter_progress
                  SET {$colName} = ?, chapter_percent = ROUND((phase_material*0.25 + phase_video*0.25 + phase_quiz*0.5)), updated_at = NOW()
                  WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?";
    $upd = $conn->prepare($updateSql);
    if (!$upd) throw new Exception("Prepare failed (update 2): " . $conn->error);
    $upd->bind_param('isii', $newValue, $student_reg_no, $cm_id, $chapter_id);
    if (!$upd->execute()) throw new Exception("Update failed: " . $upd->error);
    $upd->close();

    // Re-fetch updated chapter row
    $sel = $conn->prepare("SELECT * FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?");
    if (!$sel) throw new Exception("Prepare failed (select after update): " . $conn->error);
    $sel->bind_param('sii', $student_reg_no, $cm_id, $chapter_id);
    $sel->execute();
    $r2 = $sel->get_result();
    $updatedRow = $r2->fetch_assoc();
    $sel->close();

    $chapter_percent = intval($updatedRow['chapter_percent'] ?? 0);
    $phase_material = intval($updatedRow['phase_material']);
    $phase_video = intval($updatedRow['phase_video']);
    $phase_quiz = intval($updatedRow['phase_quiz']);

    // If quiz phase updated and meets pass requirement, maybe unlock next chapter (only if learning_type is sequential)
    $unlock_next = false;
    if ($phase_type === 'quiz' && $newValue >= $quiz_pass_threshold && $chapter_percent === 100) {
        // Check course learning type
        $q = $conn->prepare("SELECT learning_type FROM course_material WHERE cm_id = ?");
        if (!$q) throw new Exception("Prepare failed (learning_type): " . $conn->error);
        $q->bind_param('i', $cm_id);
        $q->execute();
        $resQ = $q->get_result();
        $cmRow = $resQ->fetch_assoc();
        $q->close();
        $learning_type = strtolower($cmRow['learning_type'] ?? 'flexible');

        if ($learning_type === 'sequential') {
            // find next chapter (by module.mid ordering: assuming chapter_id refers to module.mid)
            $nx = $conn->prepare("SELECT mid FROM module WHERE cm_id = ? AND mid > ? ORDER BY mid ASC LIMIT 1");
            if (!$nx) throw new Exception("Prepare failed (next chapter): " . $conn->error);
            $nx->bind_param('ii', $cm_id, $chapter_id);
            $nx->execute();
            $nxRes = $nx->get_result();
            if ($nxRow = $nxRes->fetch_assoc()) {
                $next_mid = intval($nxRow['mid']);
                // Create/update student_chapter_progress for next chapter and set unlocked = 1
                // If row not exists insert; else update unlocked = 1
                $insUp = $conn->prepare("SELECT id FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?");
                $insUp->bind_param('sii', $student_reg_no, $cm_id, $next_mid);
                $insUp->execute();
                $tmpRes = $insUp->get_result();
                if ($tmpRes->fetch_assoc()) {
                    // update unlocked
                    $u2 = $conn->prepare("UPDATE student_chapter_progress SET unlocked = 1, updated_at = NOW() WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?");
                    $u2->bind_param('sii', $student_reg_no, $cm_id, $next_mid);
                    $u2->execute();
                    $u2->close();
                } else {
                    $i2 = $conn->prepare("INSERT INTO student_chapter_progress (student_reg_no, cm_id, launch_course_id, chapter_id, phase_material, phase_video, phase_quiz, chapter_percent, unlocked, started_at, updated_at)
                                          VALUES (?, ?, NULL, ?, 0, 0, 0, 0, 1, NULL, NOW())");
                    $i2->bind_param('sis', $student_reg_no, $cm_id, $next_mid);
                    $i2->execute();
                    $i2->close();
                }
                $insUp->close();
                $unlock_next = true;
            }
            $nx->close();
        }
    }

    // Recalculate overall course percent for student: average of chapter_percent for all chapters of cm_id
    $avgStmt = $conn->prepare("SELECT AVG(chapter_percent) AS avg_prog FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ?");
    if (!$avgStmt) throw new Exception("Prepare failed (avg): " . $conn->error);
    $avgStmt->bind_param('si', $student_reg_no, $cm_id);
    $avgStmt->execute();
    $avgRes = $avgStmt->get_result();
    $avgRow = $avgRes->fetch_assoc();
    $course_percent = $avgRow && $avgRow['avg_prog'] !== null ? round(floatval($avgRow['avg_prog'])) : 0;
    $avgStmt->close();

    // Upsert student_course_progress
    $upsert = $conn->prepare("SELECT scp_id FROM student_course_progress WHERE student_reg_no = ? AND cm_id = ?");
    $upsert->bind_param('si', $student_reg_no, $cm_id);
    $upsert->execute();
    $uRes = $upsert->get_result();
    if ($uRow = $uRes->fetch_assoc()) {
        $scp_id = intval($uRow['scp_id']);
        $u1 = $conn->prepare("UPDATE student_course_progress SET course_percent = ?, updated_at = NOW() WHERE scp_id = ?");
        $u1->bind_param('ii', $course_percent, $scp_id);
        $u1->execute();
        $u1->close();
    } else {
        $i1 = $conn->prepare("INSERT INTO student_course_progress (student_reg_no, cm_id, launch_course_id, course_percent, course_started_at, updated_at) VALUES (?, ?, NULL, ?, NOW(), NOW())");
        $i1->bind_param('sii', $student_reg_no, $cm_id, $course_percent);
        $i1->execute();
        $i1->close();
    }
    $upsert->close();

    $conn->commit();

    // Success response
    http_response_code(200);
    echo json_encode([
        'status' => 200,
        'message' => 'Progress updated successfully',
        'chapter' => [
            'chapter_id' => $chapter_id,
            'phase_material' => intval($phase_material),
            'phase_video' => intval($phase_video),
            'phase_quiz' => intval($phase_quiz),
            'chapter_percent' => $chapter_percent,
            'unlocked' => intval($updatedRow['unlocked'] ?? 0)
        ],
        'course_percent' => $course_percent,
        'unlocked_next' => $unlock_next
    ]);
    exit;

} catch (Exception $e) {
    // Rollback if transaction active
    if ($conn && $conn->connect_errno === 0) {
        $conn->rollback();
    }
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    exit;
}
