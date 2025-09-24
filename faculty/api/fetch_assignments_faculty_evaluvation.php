<?php
include('../../includes/config.php');
header('Content-Type: application/json');

$launch_c_id = isset($_GET['launch_c_id']) ? intval($_GET['launch_c_id']) : null;

if (!$launch_c_id) {
    echo json_encode(['status' => 400, 'message' => 'Missing launch_c_id', 'data' => []]);
    exit;
}

// Fetch assignments along with module/chapter info, combine chapters
$sql = "SELECT 
            sa.s_ass_id,
            sa.title,
            sa.notes,
            sa.marks AS obtained_marks,   -- marks student got
            sa.submission_date,
            s.student_name,
            s.student_reg_no,
            cm.course_code,
            lc.course_name,
            a.marks AS total_marks,       -- ✅ fetch total marks from assignment table
            GROUP_CONCAT(DISTINCT m.chapter_title SEPARATOR ', ') AS chapter_titles
        FROM student_assignment sa
        INNER JOIN assignment a ON a.ass_id = sa.ass_id   -- ✅ join assignment table
        INNER JOIN student_course_approval s ON s.stu_ap_id = sa.student_id
        INNER JOIN course_material cm ON cm.cm_id = sa.c_id
        INNER JOIN launch_courses lc ON lc.id = sa.launch_id
        LEFT JOIN module m ON m.cm_id = sa.c_id
        WHERE sa.launch_id = ?
        GROUP BY sa.s_ass_id";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $launch_c_id);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode([
    'status' => count($assignments) ? 200 : 404,
    'message' => count($assignments) ? 'Assignments fetched' : 'No submissions found',
    'data' => $assignments
]);
