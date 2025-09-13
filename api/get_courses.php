<?php
header('Content-Type: application/json');
include 'config.php'; // your DB connection

$sql = "SELECT * FROM launch_courses ORDER BY id ASC"; // adjust table/column names
$result = $conn->query($sql);

$courses = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

echo json_encode($courses);
$conn->close();
?>