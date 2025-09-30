<?php
header('Content-Type: application/json');
include "../../includes/config.php"; // DB connection with $conn

$co_level = isset($_POST['co_level']) ? trim($_POST['co_level']) : '';
$course_description = isset($_POST['course_description']) ? trim($_POST['course_description']) : '';
$launch_id = isset($_POST['launch_id']) ? intval($_POST['launch_id']) : 0;

$response = ["status" => "error", "message" => "Unknown error occurred."];

if (!$co_level || !$course_description || !$launch_id) {
    http_response_code(400);
    $response = [
        "status" => "error",
        "message" => "All fields are required."
    ];
    echo json_encode($response);
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO course_outcome (co_level, course_description, launch_id, created_at) VALUES (?, ?, ?, Now())");
if (!$stmt) {
    http_response_code(500);
    $response = [
        "status" => "error",
        "message" => "Prepare failed: " . mysqli_error($conn)
    ];
    echo json_encode($response);
    exit;
}

mysqli_stmt_bind_param($stmt, "ssi", $co_level, $course_description, $launch_id);

if (mysqli_stmt_execute($stmt)) {
    $response = [
        "status" => "success",
        "message" => "Course outcome added successfully!",
        "data" => [
            "co_level" => $co_level,
            "course_description" => $course_description,
            "launch_id" => $launch_id,
            "insert_id" => mysqli_insert_id($conn)
        ]
    ];
} else {
    http_response_code(500);
    $response = [
        "status" => "error",
        "message" => "Execute failed: " . mysqli_stmt_error($stmt)
    ];
}

echo json_encode($response);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>