<?php
header('Content-Type: application/json');
include "../../includes/config.php"; // Your DB connection file, should set $conn (mysqli)

// Get launch_id from GET parameters and sanitize
$launch_id = isset($_GET['launch_id']) ? intval($_GET['launch_id']) : 0;

if (!$launch_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'launch_id is required'
    ]);
    exit;
}

// Prepare SQL query to fetch distinct co_level values for the given launch_id
$query = "SELECT DISTINCT co_level FROM course_outcome WHERE launch_id = ? ORDER BY co_level ASC";

$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database prepare failed: ' . mysqli_error($conn)
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $launch_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$levels = [];
while ($row = mysqli_fetch_assoc($result)) {
    $levels[] = $row['co_level'];
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

// Return the data as JSON
echo json_encode([
    'status' => 'success',
    'data' => $levels
]);
