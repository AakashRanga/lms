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
$seat_allotment = trim($_POST['seatAllotment'] ?? '');
$duration = trim($_POST['duration'] ?? '');
$department = trim($_POST['department'] ?? '');
$branch = trim($_POST['branch'] ?? '');
$course_type = trim($_POST['course_type'] ?? '');
$faculty_name = trim($_POST['facultyName'] ?? '');

// Validate required fields
$missing = [];
if (!$course_name)
    $missing[] = "courseName";
if (!$course_code)
    $missing[] = "courseCode";
if (!$seat_allotment)
    $missing[] = "seatAllotment";
if (!$duration)
    $missing[] = "duration";
if (!$department)
    $missing[] = "department";
if (!$branch)
    $missing[] = "branch";
if (!$course_type)
    $missing[] = "course_type";
if (!$faculty_name)
    $missing[] = "facultyName";

if (!empty($missing)) {
    http_response_code(400);
    echo json_encode([
        "status" => 400,
        "message" => "Missing required fields: " . implode(", ", $missing)
    ]);
    exit();
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO courses 
    (course_name, course_code, seat_allotment, duration, department, branch, course_type, faculty_name, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Database prepare failed: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param(
    "ssisssss",
    $course_name,
    $course_code,
    $seat_allotment,
    $duration,
    $department,
    $branch,
    $course_type,
    $faculty_name
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
