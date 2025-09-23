<?php
include '../../includes/config.php';
header('Content-Type: application/json');

$student_id = $_SESSION['userid'] ?? null;
$c_id = $_GET['cm_id'] ?? null;
$launch_id = $_GET['launch_c'] ?? null;

if (!$student_id) {
    echo json_encode(['status' => 400, 'message' => 'Unauthorized']);
    exit;
}

// Build the base query
$queryStr = "SELECT sa.s_ass_id, sa.c_id, sa.launch_id, sa.ass_id, sa.title, sa.instruction, sa.notes, sa.marks, sa.submission_date, c.course_name
             FROM student_assignment sa
             JOIN course c ON sa.c_id = c.c_id
             WHERE sa.student_id = ?";

// Parameters array for bind_param
$params = [$student_id];
$types = "i";

// Add dynamic filters if provided
if ($c_id) {
    $queryStr .= " AND sa.c_id = ?";
    $types .= "i";
    $params[] = $c_id;
}
if ($launch_id) {
    $queryStr .= " AND sa.launch_id = ?";
    $types .= "i";
    $params[] = $launch_id;
}

$queryStr .= " ORDER BY sa.submission_date DESC";

// Prepare and bind parameters dynamically
$query = $conn->prepare($queryStr);
$query->bind_param($types, ...$params);
$query->execute();
$result = $query->get_result();

$submissions = [];
while ($row = $result->fetch_assoc()) {
    $submissions[] = $row;
}

echo json_encode(['status' => 200, 'data' => $submissions]);
