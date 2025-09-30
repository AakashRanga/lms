<?php
header('Content-Type: application/json');
include "../../includes/config.php";

$id = isset($_POST['co_id']) ? intval($_POST['co_id']) : 0;
$co_level = isset($_POST['co_level']) ? trim($_POST['co_level']) : '';
$course_description = isset($_POST['course_description']) ? trim($_POST['course_description']) : '';

if (!$id || !$co_level || !$course_description) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$query = "UPDATE course_outcome SET co_level = ?, course_description = ? WHERE co_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssi", $co_level, $course_description, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success', 'message' => 'Course outcome updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>