<?php

header('Content-Type: application/json');
include "../../includes/config.php"; // DB connection

if (!isset($_SESSION['userid'])) {
    echo json_encode(["status" => 401, "message" => "Unauthorized"]);
    exit;
}

$faculty_id = $_SESSION['userid'];

$sql = "SELECT id, course_name, course_code 
        FROM launch_courses 
        WHERE faculty_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode([
    "status" => 200,
    "data" => $courses
]);
