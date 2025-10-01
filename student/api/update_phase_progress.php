<?php 
header('Content-Type: application/json; charset=utf-8');
include "../../includes/config.php";

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    $student_reg_no = $input['student_reg_no'] ?? null;
    $cm_id = isset($input['cm_id']) ? intval($input['cm_id']) : null;
    $chapter_id = isset($input['chapter_id']) ? intval($input['chapter_id']) : null;
    $phase_type = isset($input['phase_type']) ? strtolower(trim($input['phase_type'])) : null;
    $progress = isset($input['progress']) ? intval($input['progress']) : null;

    if (!$student_reg_no || !$cm_id || !$chapter_id || !$phase_type) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'Missing required parameters']);
        exit;
    }

    $allowed_phases = ['material', 'video', 'quiz'];
    if (!in_array($phase_type, $allowed_phases)) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'phase_type must be material, video or quiz']);
        exit;
    }

    if ($progress < 0) $progress = 0;
    if ($progress > 100) $progress = 100;

    $conn->begin_transaction();

    // Ensure row exists
    $stmt = $conn->prepare("SELECT * FROM student_chapter_progress WHERE student_reg_no=? AND cm_id=? AND chapter_id=? FOR UPDATE");
    $stmt->bind_param("sii", $student_reg_no, $cm_id, $chapter_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        $ins = $conn->prepare("INSERT INTO student_chapter_progress 
            (student_reg_no, cm_id, launch_course_id, chapter_id, phase_material, phase_video, phase_quiz, chapter_percent, unlocked, started_at, updated_at) 
            VALUES (?, ?, NULL, ?, 0, 0, 0, 0, 1, NOW(), NOW())");
        $ins->bind_param("sis", $student_reg_no, $cm_id, $chapter_id);
        $ins->execute();
        $ins->close();
        $row = ['phase_material'=>0,'phase_video'=>0,'phase_quiz'=>0];
    }

    // Map column
    $colName = "phase_" . $phase_type;
    $oldValue = intval($row[$colName] ?? 0);
    $newValue = max($oldValue, $progress);

    // Update phase
    $upd = $conn->prepare("UPDATE student_chapter_progress SET {$colName}=?, updated_at=NOW() 
                           WHERE student_reg_no=? AND cm_id=? AND chapter_id=?");
    $upd->bind_param("isii", $newValue, $student_reg_no, $cm_id, $chapter_id);
    $upd->execute();
    $upd->close();

    // Recalculate chapter_percent
    $recalc = $conn->prepare("UPDATE student_chapter_progress
        SET chapter_percent=ROUND((phase_material*0.25 + phase_video*0.25 + phase_quiz*0.5)), updated_at=NOW()
        WHERE student_reg_no=? AND cm_id=? AND chapter_id=?");
    $recalc->bind_param("sii", $student_reg_no, $cm_id, $chapter_id);
    $recalc->execute();
    $recalc->close();

    // Fetch updated
    $sel = $conn->prepare("SELECT * FROM student_chapter_progress WHERE student_reg_no=? AND cm_id=? AND chapter_id=?");
    $sel->bind_param("sii", $student_reg_no, $cm_id, $chapter_id);
    $sel->execute();
    $updatedRow = $sel->get_result()->fetch_assoc();
    $sel->close();

    $chapter_percent = intval($updatedRow['chapter_percent']);

    // Unlock next chapter if sequential
    $unlock_next = false;
    $q = $conn->prepare("SELECT learning_type FROM course_material WHERE cm_id=?");
    $q->bind_param("i", $cm_id);
    $q->execute();
    $cmRow = $q->get_result()->fetch_assoc();
    $q->close();
    $learning_type = strtolower($cmRow['learning_type'] ?? "flexible");

    if ($learning_type === "sequential" && $chapter_percent == 100) {
        $nx = $conn->prepare("SELECT mid FROM module WHERE cm_id=? AND mid>? ORDER BY mid ASC LIMIT 1");
        $nx->bind_param("ii", $cm_id, $chapter_id);
        $nx->execute();
        if ($nxRow = $nx->get_result()->fetch_assoc()) {
            $next_mid = $nxRow['mid'];
            $conn->query("INSERT INTO student_chapter_progress 
                (student_reg_no, cm_id, launch_course_id, chapter_id, phase_material, phase_video, phase_quiz, chapter_percent, unlocked, started_at, updated_at)
                VALUES ('$student_reg_no',$cm_id,NULL,$next_mid,0,0,0,0,1,NOW(),NOW())
                ON DUPLICATE KEY UPDATE unlocked=1, updated_at=NOW()");
            $unlock_next = true;
        }
        $nx->close();
    }

    // ✅ Correct overall course progress
    $courseSql = "SELECT 
                    (SUM(COALESCE(scp.chapter_percent,0)) / COUNT(m.mid)) AS course_percent
                  FROM module m
                  LEFT JOIN student_chapter_progress scp
                    ON scp.cm_id=m.cm_id AND scp.chapter_id=m.mid AND scp.student_reg_no=?
                  WHERE m.cm_id=?";
    $calc = $conn->prepare($courseSql);
    $calc->bind_param("si", $student_reg_no, $cm_id);
    $calc->execute();
    $course_percent = round($calc->get_result()->fetch_assoc()['course_percent'] ?? 0);
    $calc->close();

    // Upsert into student_course_progress
    $conn->query("INSERT INTO student_course_progress (student_reg_no, cm_id, launch_course_id, course_percent, course_started_at, updated_at)
                  VALUES ('$student_reg_no',$cm_id,NULL,$course_percent,NOW(),NOW())
                  ON DUPLICATE KEY UPDATE course_percent=$course_percent, updated_at=NOW()");

    $conn->commit();

    echo json_encode([
        "status"=>200,
        "message"=>"Progress updated",
        "chapter"=>[
            "chapter_id"=>$chapter_id,
            "phase_material"=>intval($updatedRow['phase_material']),
            "phase_video"=>intval($updatedRow['phase_video']),
            "phase_quiz"=>intval($updatedRow['phase_quiz']),
            "chapter_percent"=>$chapter_percent,
            "unlocked"=>intval($updatedRow['unlocked'])
        ],
        "course_percent"=>$course_percent,
        "unlocked_next"=>$unlock_next
    ]);
} catch (Exception $e) {
    if ($conn && $conn->connect_errno===0) $conn->rollback();
    http_response_code(500);
    echo json_encode(["status"=>500,"message"=>$e->getMessage()]);
}
