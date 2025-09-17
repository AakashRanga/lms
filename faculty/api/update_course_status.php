<?php
header('Content-Type: application/json');
include "../../includes/config.php";

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $approval_id = $_POST['approval_id'] ?? null;
        $status      = $_POST['status'] ?? null;

        if (!$approval_id || !$status) {
            echo json_encode(["status" => "error", "message" => "Missing required fields"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE student_course_approval 
                                SET status = ?, updated_at = NOW() 
                                WHERE stu_ap_id = ?");
        $stmt->bind_param("si", $status, $approval_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Status updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Update failed"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();
?>
