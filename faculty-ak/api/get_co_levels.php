<?php
header('Content-Type: application/json');
include "../../includes/config.php"; // Your DB connection file, should set $conn (mysqli)

$query = "SELECT co_id,co_level,course_description FROM course_outcome ORDER BY co_level ASC";
$result = $conn->query($query);

$coLevels = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $coLevels[] = $row;
    }
}

echo json_encode($coLevels);
