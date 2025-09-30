<?php
header('Content-Type: application/json');
include "../../includes/config.php";

$id = $_POST['co_id'];



$query = "DELETE FROM course_outcome WHERE co_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success', 'message' => 'Course outcome deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>