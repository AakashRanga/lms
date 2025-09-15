<?php
// Disable error output to prevent breaking JSON
error_reporting(0);

// Set headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept");

// Include database config
include "../../includes/config.php";

// Only accept POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => 405,
        "message" => "Invalid Request Method"
    ]);
    exit();
}

// Collect form data
$course_name = trim($_POST['courseName'] ?? '');
$course_code = trim($_POST['courseCode'] ?? '');


// Validate required fields
$missing = [];
if (!$course_name)
    $missing[] = "courseName";
if (!$course_code)
    $missing[] = "courseCode";

if (!empty($missing)) {
    http_response_code(400);
    echo json_encode([
        "status" => 400,
        "message" => "Missing required fields: " . implode(", ", $missing)
    ]);
    exit();
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO course
    (course_name, course_code, created_at)
    VALUES (?, ?, NOW())");

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Database prepare failed: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param(
    "ss",
    $course_name,
    $course_code
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => 200,
        "message" => "Course added successfully"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Database insertion failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
