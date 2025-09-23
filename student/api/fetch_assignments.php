<?php
include '../../includes/config.php'; // DB connection

header('Content-Type: application/json'); // ✅ Tell browser it's JSON

$cm_id = $_GET['cm_id'] ?? null;
$launch_c = $_GET['launch_c'] ?? null;

if (!$cm_id || !$launch_c) {
    echo json_encode([
        'status' => 400,
        'message' => 'Missing parameters'
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT ass_id, title FROM assignment WHERE c_id = ? AND launch_id = ?");
$stmt->bind_param("ii", $cm_id, $launch_c);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode([
    'status' => 200,
    'data' => $assignments
]);
