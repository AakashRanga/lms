<?php
header('Content-Type: application/json');
include "../../includes/config.php"; // DB connection $conn

$launch_id = isset($_GET['launch_id']) ? intval($_GET['launch_id']) : 0;

// Basic validation
if (!$launch_id) {
    echo json_encode(['status' => 'error', 'message' => 'launch_id is required']);
    exit;
}

// Query without search/level filters
$query = "SELECT co_id,co_level, course_description 
          FROM course_outcome 
          WHERE launch_id = ? 
          ORDER BY co_level";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $launch_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$outcomes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $outcomes[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $outcomes]);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>