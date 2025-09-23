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

$user_id = $_SESSION['userid'];

// Get cm_id and launch_c from URL
$cm_id = isset($_GET['cm_id']) ? intval($_GET['cm_id']) : 0;
$launch_id = isset($_GET['launch_c']) ? intval($_GET['launch_c']) : 0;

if ($cm_id <= 0 || $launch_id <= 0) {
    echo json_encode([
        'status' => 400,
        'message' => 'Invalid course material or launch ID',
        'data' => []
    ]);
    exit;
}

// Fetch assignments directly from assignment table
$stmt = $conn->prepare("
    SELECT a.ass_id, a.title, a.notes, a.marks, a.instruction, a.due_date, c.course_name,a.created_at
    FROM assignment a
    INNER JOIN course c ON a.c_id = c.c_id
    WHERE a.launch_id = ? AND a.c_id = ?
    ORDER BY a.due_date DESC
");
$stmt->bind_param("ii", $launch_id, $cm_id);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = [
        'title' => $row['title'],
        'course' => $row['course_name'],
        'uploaded_files' => $row['notes'], // assuming notes contains file links
        'marks' => $row['marks'],
        'instruction' => $row['instruction'],
        'due_date' => date('Y-m-d', strtotime($row['due_date'])),
        'submission_date' => date('Y-m-d', strtotime($row['created_at'])),

    ];
}

$stmt->close();

echo json_encode([
    'status' => 200,
    'message' => 'Assignments fetched successfully',
    'data' => $assignments
]);
