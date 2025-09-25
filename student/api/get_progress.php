<?php
header('Content-Type: application/json; charset=utf-8');
include "../../includes/config.php";

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_GET;

    $student_reg_no =  $_SESSION['userid'];
    $cm_id = isset($input['cm_id']) ? intval($input['cm_id']) : null;

    if (!$student_reg_no || !$cm_id) {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'Missing required params: student_reg_no, cm_id']);
        exit;
    }

    // Fetch all modules (chapters) for the course_material (cm_id)
    $mstmt = $conn->prepare("SELECT mid, chapter_no, chapter_title, materials, flipped_class FROM module WHERE cm_id = ? ORDER BY mid ASC");
    if (!$mstmt) throw new Exception("Prepare failed (modules): " . $conn->error);
    $mstmt->bind_param('i', $cm_id);
    $mstmt->execute();
    $mres = $mstmt->get_result();
    $modules = [];
    while ($mrow = $mres->fetch_assoc()) {
        $modules[$mrow['mid']] = [
            'mid' => intval($mrow['mid']),
            'chapter_no' => $mrow['chapter_no'],
            'chapter_title' => $mrow['chapter_title'],
            'materials' => $mrow['materials'],
            'flipped_class' => $mrow['flipped_class'],
            // placeholders we'll fill from student_chapter_progress if exists
            'phase_material' => 0,
            'phase_video' => 0,
            'phase_quiz' => 0,
            'chapter_percent' => 0,
            'unlocked' => 0
        ];
    }
    $mstmt->close();

    // If no modules
    if (empty($modules)) {
        http_response_code(404);
        echo json_encode(['status' => 404, 'message' => 'No chapters found for this course (cm_id).']);
        exit;
    }

    // Fetch student's progress rows for these chapters
    $mid_list = array_keys($modules);
    // build IN clause safely
    $placeholders = implode(',', array_fill(0, count($mid_list), '?'));
    $types = str_repeat('i', count($mid_list));
    $params = $mid_list;

    $sql = "SELECT * FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ? AND chapter_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Prepare failed (progress fetch): " . $conn->error);

    // bind params: first student_reg_no (s), then cm_id (i), then mids...
    $bind_types = 'si' . $types;
    $bind_params = array_merge([$bind_types, $student_reg_no, $cm_id], $params);
    // use call_user_func_array for dynamic bind
    $refs = [];
    foreach ($bind_params as $k => $v) {
        $refs[$k] = &$bind_params[$k];
    }
    call_user_func_array([$stmt, 'bind_param'], $refs);
    $stmt->execute();
    $pres = $stmt->get_result();
    while ($prow = $pres->fetch_assoc()) {
        $mid = intval($prow['chapter_id']);
        if (isset($modules[$mid])) {
            $modules[$mid]['phase_material'] = intval($prow['phase_material']);
            $modules[$mid]['phase_video'] = intval($prow['phase_video']);
            $modules[$mid]['phase_quiz'] = intval($prow['phase_quiz']);
            $modules[$mid]['chapter_percent'] = intval($prow['chapter_percent']);
            $modules[$mid]['unlocked'] = intval($prow['unlocked']);
        }
    }
    $stmt->close();

    // Calculate overall course_percent (average of chapter_percent) if not stored
    $sum = 0; $count = 0;
    foreach ($modules as $mod) {
        $sum += intval($mod['chapter_percent']);
        $count++;
    }
    $course_percent = $count > 0 ? round($sum / $count) : 0;

    // Also try to read student_course_progress for authoritative value (if exists)
    $cstmt = $conn->prepare("SELECT course_percent FROM student_course_progress WHERE student_reg_no = ? AND cm_id = ?");
    if ($cstmt) {
        $cstmt->bind_param('si', $student_reg_no, $cm_id);
        $cstmt->execute();
        $cres = $cstmt->get_result();
        if ($crow = $cres->fetch_assoc()) {
            $course_percent = intval($crow['course_percent']);
        }
        $cstmt->close();
    }

    http_response_code(200);
    echo json_encode([
        'status' => 200,
        'message' => 'Progress fetched',
        'course_percent' => $course_percent,
        'chapters' => array_values($modules)
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    exit;
}
