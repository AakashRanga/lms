<?php
include '../../includes/config.php'; // DB connection

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    echo json_encode([
        'status' => 401,
        'message' => 'User not logged in',
        'data' => []
    ]);
    exit;
}

$faculty_id = $_SESSION['userid'];

// Get launch_id from URL parameter launch_c_id
$launch_id = isset($_GET['launch_c_id']) ? intval($_GET['launch_c_id']) : 0;


if ($launch_id <= 0) {
    echo json_encode([
        'status' => 400,
        'message' => 'Invalid launch ID',
        'data' => []
    ]);
    exit;
}

// Fetch all assignment columns for this faculty and launch
$stmt = $conn->prepare("
    SELECT c_id, launch_id, faculty_id, title, instruction, notes, marks, due_date, created_at
    FROM assignment
    WHERE faculty_id = ? AND launch_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("ii", $faculty_id, $launch_id);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = [
        'c_id' => $row['c_id'],
        'launch_id' => $row['launch_id'],
        'faculty_id' => $row['faculty_id'],
        'title' => $row['title'],
        'instruction' => $row['instruction'],
        'notes' => $row['notes'],
        'marks' => $row['marks'],
        'due_date' => date('Y-m-d', strtotime($row['due_date'])),
        'assigned_date' => date('Y-m-d', strtotime($row['created_at']))
    ];
}

$stmt->close();

// JSON response
echo json_encode([
    'status' => 200,
    'message' => 'Assignments fetched successfully',
    'data' => $assignments
]);
