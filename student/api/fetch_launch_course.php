<?php
header('Content-Type: application/json');
include "../../includes/config.php";

// $register_number = isset($_GET['register_number']) ? $_GET['register_number'] : null;
// $register_number=21617;
$register_number =  $_SESSION["userid"];   
$sql = "SELECT lc.slot, lc.id, lc.course_code, lc.course_name, lc.faculty_name, lc.seat_allotment,
               sca.status AS student_status
        FROM launch_courses lc
        LEFT JOIN student_course_approval sca 
               ON lc.id = sca.launch_c_id 
              AND sca.student_reg_no = '$register_number'
        ORDER BY lc.slot ASC, lc.course_code ASC;";

$result = $conn->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slot = $row['slot'];
        if (!isset($response[$slot])) {
            $response[$slot] = [];
        }

        $response[$slot][] = [
            "launch_c_id"   => $row['id'],
            "course_code"   => $row['course_code'],
            "course_name"   => $row['course_name'],
            "faculty_name"  => $row['faculty_name'],
            "seat_count"    => $row['seat_allotment'],
            "student_status"=> $row['student_status'] ?? null
        ];
    }
}

echo json_encode([
    "status" => "success",
    "data"   => $response
], JSON_PRETTY_PRINT);

$conn->close();
