<?php
include '../../includes/config.php';

header('Content-Type: application/json');
$user_id   = $_SESSION['userid'];
// Fetch all assignments with course name, course_material cm_id and launch_id
$query = "SELECT 
            a.ass_id,
            a.title AS assignment_title,
            a.instruction,
            a.notes,
            a.marks,
            a.due_date,
            a.c_id,
            c.course_name,
            cm.cm_id,
            cm.launch_course_id As launch_id,
             CASE 
                WHEN sa.s_ass_id  IS NOT NULL THEN 'Submitted'
                ELSE 'Pending'
            END AS submission_status
          FROM assignment a
          LEFT JOIN course c 
            ON a.c_id = c.c_id
          LEFT JOIN course_material cm
            ON a.c_id = cm.cm_id
             LEFT JOIN student_assignment sa 
        ON sa.ass_id = a.ass_id AND sa.student_id = $user_id
          ORDER BY a.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode($assignments);
?>