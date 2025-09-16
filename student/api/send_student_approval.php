<?php
header('Content-Type: application/json');
include "../../includes/config.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method", 405);
    }

    $launch_c_id    = $_POST['launch_c_id'] ?? null;
    $student_name   = "Sai Amrish";  // For now hardcoded
    $student_reg_no = "21617";       // For now hardcoded
    $slot           = $_POST['slot'] ?? null;

    if (!$launch_c_id || !$student_name || !$student_reg_no || !$slot) {
        throw new Exception("Missing required fields", 400);
    }

    // ✅ Step 1: Check if already applied
    $checkStmt = $conn->prepare("
        SELECT stu_ap_id 
        FROM student_course_approval 
        WHERE launch_c_id = ? AND student_reg_no = ?
        LIMIT 1
    ");
    $checkStmt->bind_param("ss", $launch_c_id, $student_reg_no);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Already exists
        echo json_encode([
            "status" => "exists",
            "message" => "You have already applied for this course"
        ]);
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();

    // ✅ Step 2: Insert new approval if not exists
    $insertStmt = $conn->prepare("
        INSERT INTO student_course_approval 
        (launch_c_id, student_name, student_reg_no, slot, status, created_at) 
        VALUES (?, ?, ?, ?, 'pending', NOW())
    ");
    $insertStmt->bind_param("ssss", $launch_c_id, $student_name, $student_reg_no, $slot);

    if (!$insertStmt->execute()) {
        throw new Exception("Database insert failed: " . $insertStmt->error, 500);
    }

    echo json_encode([
        "status" => "success",
        "message" => "Approval sent successfully"
    ]);

    $insertStmt->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
