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
    session_start();

    // Collect form data
    $course_name = trim($_POST['courseName'] ?? '');
    $course_code = trim($_POST['courseCode'] ?? '');
    $course_id = trim($_POST['courseid'] ?? '');
    $seat_allotment = trim($_POST['seatAllotment'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $course_type = trim($_POST['course_type'] ?? '');
    $slot = trim($_POST['slot'] ?? '');

    // Validate required fields
    $missing = [];
    if (!$course_name) $missing[] = "courseName";
    if (!$course_code) $missing[] = "courseCode";
    if (!$seat_allotment) $missing[] = "seatAllotment";
    if (!$duration) $missing[] = "duration";
    if (!$course_type) $missing[] = "course_type";

    if (!empty($missing)) {
        http_response_code(400);
        echo json_encode([
            "status" => 400,
            "message" => "Missing required fields: " . implode(", ", $missing)
        ]);
        exit();
    }

    // Session values (faculty)
    $faculty_name = $_SESSION['name'] ?? '';
    $faculty_id   = $_SESSION['userid'] ?? '';

    if (!$faculty_id || !$faculty_name) {
        http_response_code(401);
        echo json_encode([
            "status" => 401,
            "message" => "Unauthorized: Faculty not logged in"
        ]);
        exit();
    }

    // ✅ Check if course already exists for same faculty_id + course_code
    $check = $conn->prepare("SELECT id FROM launch_courses WHERE faculty_id = ? AND course_code = ?");
    $check->bind_param("ss", $faculty_id, $course_code);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        http_response_code(409);
        echo json_encode([
            "status" => 409,
            "message" => "Course already exists for this faculty and course code"
        ]);
        $check->close();
        exit();
    }
    $check->close();

    // ✅ Insert into database
    $stmt = $conn->prepare("INSERT INTO launch_courses 
        (course_name, course_code, c_id, seat_allotment, duration, department, branch, course_type, slot, faculty_name, faculty_id, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $department = "Null";
    $branch = "Null";

    $stmt->bind_param(
        "sssisssssss",
        $course_name,
        $course_code,
        $course_id,
        $seat_allotment,
        $duration,
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
