<?php
header('Content-Type: application/json');
include "../../includes/config.php";

// Fetch distinct slots and group courses
$sql = "SELECT slot, id, course_code, course_name, faculty_name, seat_allotment 
        FROM launch_courses 
        ORDER BY slot ASC, course_code ASC";

$result = $conn->query($sql);

$response = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slot = $row['slot'];
        if (!isset($response[$slot])) {
            $response[$slot] = [];
        }
        $response[$slot][] = [
            "launch_c_id"  => $row['id'],
            "course_code"  => $row['course_code'],
            "course_name"  => $row['course_name'],
            "faculty_name" => $row['faculty_name'],
            "seat_count"   => $row['seat_allotment']
        ];
    }
}

echo json_encode([
    "status" => "success",
    "data"   => $response
], JSON_PRETTY_PRINT);

$conn->close();
?>


