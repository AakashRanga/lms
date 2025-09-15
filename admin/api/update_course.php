<?php
// update_course.php
header('Content-Type: application/json');

include "../../includes/config.php";

$courseId = trim($_POST['courseId'] ?? '');
$course_name = trim($_POST['courseName'] ?? '');
$course_code = trim($_POST['courseCode'] ?? '');
$status =  trim($_POST['status'] ?? '');


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



// Update query
$sql = "UPDATE course
        SET course_name = '$course_name', 
            course_code = '$course_code', 
            status = '$status' 
        WHERE c_id = $courseId";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "message" => "Course updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating course: " . mysqli_error($conn)]);
}

mysqli_close($conn);
