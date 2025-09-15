<?php
header('Content-Type: application/json');
include "../../includes/config.php";

$sql = "SELECT * FROM course ORDER BY c_id ASC"; // adjust table/column names
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