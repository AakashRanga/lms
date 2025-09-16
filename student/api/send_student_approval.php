<?php
header('Content-Type: application/json');
include "../../includes/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $launch_c_id   = $_POST['launch_c_id'] ?? null;
    $student_name  = "Sai Amrish";
    $student_reg_no= "007";
    $slot          = $_POST['slot'] ?? null;

    if (!$launch_c_id || !$student_name || !$student_reg_no || !$slot) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO student_course_approval 
        (launch_c_id, student_name, student_reg_no, slot, status, created_at) 
        VALUES (?, ?, ?, ?, 'pending', NOW())");

    $stmt->bind_param("ssss", $launch_c_id, $student_name, $student_reg_no, $slot);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Approval sent successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
