<?php
header('Content-Type: application/json');
include "../../includes/config.php";

$faculty_id = $_SESSION['userid'];
$sql = "SELECT * from launch_courses where faculty_id=$faculty_id ";
$result = $conn->query($sql);

$courses = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

echo json_encode($courses);
$conn->close();
