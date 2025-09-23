<?php
include '../../includes/config.php';
header('Content-Type: application/json');

$student_id = $_SESSION['userid'] ?? null;

if (!$student_id) {
    echo json_encode(['status' => 400, 'message' => 'Unauthorized']);
    exit;
}

$query = $conn->prepare("SELECT sa.s_ass_id, sa.ass_id, sa.title, sa.instruction, sa.notes, sa.marks, sa.submission_date, c.course_name 
                         FROM student_assignment sa
                         JOIN course c ON sa.c_id = c.c_id
                         WHERE sa.student_id = ?
                         ORDER BY sa.submission_date DESC");
$query->bind_param("i", $student_id);
$query->execute();
$result = $query->get_result();

$submissions = [];
while ($row = $result->fetch_assoc()) {
    $submissions[] = $row;
}

echo json_encode(['status' => 200, 'data' => $submissions]);
