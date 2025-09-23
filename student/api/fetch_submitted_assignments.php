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

// Get cm_id and launch_c (launch_id) from URL
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

// Fetch submitted assignments
$stmt = $conn->prepare("
    SELECT a.title, a.notes, a.due_date, a.created_at, s.marks,s.due_date
    FROM assignment s
    INNER JOIN assignment a ON s.ass_id = a.c_id
    INNER JOIN course_material cm ON cm.cm_id = a.c_id
    WHERE s.c_id = ? AND a.launch_id = ? AND cm.cm_id = ?
    ORDER BY s.created_at DESC
");
$stmt->bind_param("iii", $user_id, $launch_id, $cm_id);
$stmt->execute();
$result = $stmt->get_result();

$submissions = [];
while ($row = $result->fetch_assoc()) {
    $submissions[] = [
        'title' => $row['title'],
        'uploaded_files' => $row['notes'], // can be comma-separated links
        'marks' => $row['marks'],
        'feedback' => $row['feedback'] ?? '',
        'due_date' => $row['due_date'],
        'submitted_at' => date('Y-m-d', strtotime($row['created_at']))
    ];
}

$stmt->close();

// JSON response
echo json_encode([
    'status' => 200,
    'message' => 'Submitted assignments fetched successfully',
    'data' => $submissions
]);
