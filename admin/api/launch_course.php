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

try {
    // Collect form data
    $course_name    = trim($_POST['courseName'] ?? '');
    $course_code    = trim($_POST['courseCode'] ?? '');
    $course_id      = trim($_POST['courseid'] ?? '');
    $seat_allotment = trim($_POST['seatAllotment'] ?? '');
   
    $course_type    = trim($_POST['course_type'] ?? '');
    $slot           = trim($_POST['slot'] ?? '');
    $faculty_id     = $_POST['faculty_id'] ?? '';

    // Validate required fields
    $missing = [];
    if (!$course_name) $missing[] = "courseName";
    if (!$course_code) $missing[] = "courseCode";
    if (!$seat_allotment) $missing[] = "seatAllotment";
    
    if (!$course_type) $missing[] = "course_type";
    if (!$faculty_id) $missing[] = "faculty_id";

    if (!empty($missing)) {
        http_response_code(400);
        echo json_encode([
            "status" => 400,
            "message" => "Missing required fields: " . implode(", ", $missing)
        ]);
        exit();
    }

    // ✅ Get faculty name from login table
    $faculty_query = $conn->prepare("SELECT name FROM lms_login WHERE u_id = ?");
    $faculty_query->bind_param("i", $faculty_id);
    $faculty_query->execute();
    $faculty_query->bind_result($faculty_name);
    $faculty_query->fetch();
    $faculty_query->close();

    if (!$faculty_name) {
        http_response_code(404);
        echo json_encode([
            "status" => 404,
            "message" => "Faculty not found"
        ]);
        exit();
    }

    // ✅ Check if slot already exists for same c_id + slot
    $check = $conn->prepare("SELECT id FROM launch_courses WHERE c_id = ? AND slot = ?");
    $check->bind_param("ss", $course_id, $slot);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        http_response_code(409);
        echo json_encode([
            "status" => 409,
            "message" => "Slot is already present for this course"
        ]);
        $check->close();
        exit();
    }
    $check->close();

    // ✅ Insert into database
    $stmt = $conn->prepare("INSERT INTO launch_courses 
        (course_name, course_code, c_id, seat_allotment, department, branch, course_type, slot, faculty_name, faculty_id, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $department = "Null";
    $branch = "Null";

    $stmt->bind_param(
        "sssissssss",
        $course_name,
        $course_code,
        $course_id,
        $seat_allotment,
        $department,
        $branch,
        $course_type,
        $slot,
        $faculty_name,
        $faculty_id
    );

    if ($stmt->execute()) {
        echo json_encode([
            "status" => 200,
            "message" => "Course added successfully"
        ]);
    } else {
        throw new Exception("Database insertion failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => $e->getMessage()
    ]);
}
?>
