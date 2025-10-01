<?php
header('Content-Type: application/json');
include "../../includes/config.php";

$launch_c_id = isset($_GET['launch_c_id']) ? intval($_GET['launch_c_id']) : 0;

try {
    if ($launch_c_id > 0) {
        $query = "SELECT co_id, co_level, course_description 
                  FROM course_outcome 
                  WHERE launch_id = ? 
                  ORDER BY co_id ASC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $launch_c_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $query = "SELECT co_id, co_level, course_description 
                  FROM course_outcome 
                  ORDER BY co_id ASC";
        $result = $conn->query($query);
    }

    if (!$result || $result->num_rows === 0) {
        echo json_encode([
            "status" => 404,
            "message" => "No data found"
        ]);
        exit;
    }

    $coLevels = [];
    while ($row = $result->fetch_assoc()) {
        $coLevels[] = $row;
    }

    echo json_encode([
        "status" => 200,
        "data" => $coLevels
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => 500,
        "message" => "Server error: " . $e->getMessage()
    ]);
}
