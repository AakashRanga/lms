<?php
header('Content-Type: application/json');
include "../../includes/config.php";

$sql = "SELECT *
FROM launch_courses
ORDER BY 
    CASE WHEN status = 'pending' THEN 0 ELSE 1 END,  -- Pending first
    created_at ASC,  -- Oldest date first
    id ASC;
"; // adjust table/column names
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