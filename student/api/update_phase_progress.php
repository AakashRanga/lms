<?php
header('Content-Type: application/json; charset=utf-8');
include "../../includes/config.php";

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    $student_reg_no = isset($input['student_reg_no']) ? trim($input['student_reg_no']) : null;
    $cm_id          = isset($input['cm_id']) ? intval($input['cm_id']) : null;
    $chapter_id     = isset($input['chapter_id']) ? intval($input['chapter_id']) : null;
    $phase_type     = isset($input['phase_type']) ? strtolower(trim($input['phase_type'])) : null;
    $progress       = isset($input['progress']) ? intval($input['progress']) : null;

    if (!$student_reg_no || !$cm_id || !$chapter_id || !$phase_type) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'Missing required parameters']);
        exit;
    }

    $allowed_phases = ['material', 'video', 'quiz'];
    if (!in_array($phase_type, $allowed_phases)) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'Invalid phase_type']);
        exit;
    }

    if ($progress === null) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'progress (0-100) is required']);
        exit;
    }

    if ($progress < 0) $progress = 0;
    if ($progress > 100) $progress = 100;

    $conn->begin_transaction();

    // Ensure row exists in student_chapter_progress
    $sqlCheck = "SELECT * FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ? FOR UPDATE";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param('sii', $student_reg_no, $cm_id, $chapter_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $phase_material = intval($row['phase_material']);
        $phase_video    = intval($row['phase_video']);
        $phase_quiz     = intval($row['phase_quiz']);
        $unlocked       = intval($row['unlocked']);
    } else {
        $insSql = "INSERT INTO student_chapter_progress 
                   (student_reg_no, cm_id, launch_course_id, chapter_id, 
                    phase_material, phase_video, phase_quiz, chapter_percent, unlocked, started_at, updated_at)
                   VALUES (?, ?, NULL, ?, 0, 0, 0, 0, 0, NULL, NOW())";
        $ins = $conn->prepare($insSql);
        $ins->bind_param('sis', $student_reg_no, $cm_id, $chapter_id);
        $ins->execute();
        $ins->close();

        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $phase_material = 0;
        $phase_video    = 0;
        $phase_quiz     = 0;
        $unlocked       = 0;
    }
    $stmt->close();

    // Update progress
    $colName  = ($phase_type === 'material') ? 'phase_material' :
                (($phase_type === 'video') ? 'phase_video' : 'phase_quiz');
    $oldValue = intval($row[$colName] ?? 0);
    $newValue = max($oldValue, $progress);

    $updateSql = "UPDATE student_chapter_progress
                  SET {$colName} = ?, 
                      chapter_percent = ROUND((phase_material*0.25 + phase_video*0.25 + phase_quiz*0.5)),
                      updated_at = NOW()
                  WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?";
    $upd = $conn->prepare($updateSql);
    $upd->bind_param('isii', $newValue, $student_reg_no, $cm_id, $chapter_id);
    $upd->execute();
    $upd->close();

    // Fetch updated chapter row
    $sel = $conn->prepare("SELECT * FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?");
    $sel->bind_param('sii', $student_reg_no, $cm_id, $chapter_id);
    $sel->execute();
    $updatedRow = $sel->get_result()->fetch_assoc();
    $sel->close();

    $chapter_percent = intval($updatedRow['chapter_percent'] ?? 0);
    $phase_material  = intval($updatedRow['phase_material']);
    $phase_video     = intval($updatedRow['phase_video']);
    $phase_quiz      = intval($updatedRow['phase_quiz']);
    $unlocked        = intval($updatedRow['unlocked']);

    // --- Unlock current chapter if 100% ---
    if ($chapter_percent === 100 && $unlocked == 0) {
        $u2 = $conn->prepare("UPDATE student_chapter_progress 
                              SET unlocked = 1, updated_at = NOW() 
                              WHERE student_reg_no = ? AND cm_id = ? AND chapter_id = ?");
        $u2->bind_param('sii', $student_reg_no, $cm_id, $chapter_id);
        $u2->execute();
        $u2->close();
        $unlocked = 1;
    }

    // Recalculate overall course percent
    $avgStmt = $conn->prepare("SELECT AVG(chapter_percent) AS avg_prog 
                               FROM student_chapter_progress 
                               WHERE student_reg_no = ? AND cm_id = ?");
    $avgStmt->bind_param('si', $student_reg_no, $cm_id);
    $avgStmt->execute();
    $avgRow = $avgStmt->get_result()->fetch_assoc();
    $course_percent = $avgRow && $avgRow['avg_prog'] !== null ? round(floatval($avgRow['avg_prog'])) : 0;
    $avgStmt->close();

    // Upsert into student_course_progress
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
        $i1 = $conn->prepare("INSERT INTO student_course_progress 
                              (student_reg_no, cm_id, launch_course_id, course_percent, course_started_at, updated_at) 
                              VALUES (?, ?, NULL, ?, NOW(), NOW())");
        $i1->bind_param('sii', $student_reg_no, $cm_id, $course_percent);
        $i1->execute();
        $i1->close();
    }
    $upsert->close();

    $conn->commit();

    http_response_code(200);
    echo json_encode([
        'status' => 200,
        'message' => 'Progress updated successfully',
        'chapter' => [
            'chapter_id'     => $chapter_id,
            'phase_material' => $phase_material,
            'phase_video'    => $phase_video,
            'phase_quiz'     => $phase_quiz,
            'chapter_percent'=> $chapter_percent,
            'unlocked'       => $unlocked
        ],
        'course_percent' => $course_percent
    ]);
    exit;

} catch (Exception $e) {
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
