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

        // --- 1. Get current status + launch_c_id ---
        $query = $conn->prepare("SELECT status, launch_c_id 
                                 FROM student_course_approval 
                                 WHERE stu_ap_id = ?");
        $query->bind_param("i", $approval_id);
        $query->execute();
        $query->bind_result($current_status, $launch_c_id);
        if (!$query->fetch()) {
            echo json_encode(["status" => "error", "message" => "Approval not found"]);
            $query->close();
            exit;
        }
        $query->close();

        // --- 2. Update only if status actually changes ---
        if ($current_status === $status) {
            echo json_encode(["status" => "success", "message" => "No change (already $status)"]);
            exit;
        }

        // --- 3. Update approval status ---
        $stmt = $conn->prepare("UPDATE student_course_approval 
                                SET status = ?, updated_at = NOW() 
                                WHERE stu_ap_id = ?");
        $stmt->bind_param("si", $status, $approval_id);

        if (!$stmt->execute()) {
            echo json_encode(["status" => "error", "message" => "Failed to update status"]);
            $stmt->close();
            exit;
        }
        $stmt->close();

        // --- 4. Seat adjustment ---
        if ($status === 'approved' && $current_status !== 'approved') {
            // Reduce only once when moving to approved
            $seatStmt = $conn->prepare("UPDATE launch_courses 
                                        SET seat_allotment = seat_allotment - 1 
                                        WHERE id = ? AND seat_allotment > 0");
            $seatStmt->bind_param("i", $launch_c_id);
            $seatStmt->execute();
            $seatStmt->close();
        } elseif ($status !== 'approved' && $current_status === 'approved') {
            // Optional: free seat if student is moved away from approved
            $seatStmt = $conn->prepare("UPDATE launch_courses 
                                        SET seat_allotment = seat_allotment + 1 
                                        WHERE id = ?");
            $seatStmt->bind_param("i", $launch_c_id);
            $seatStmt->execute();
            $seatStmt->close();
        }

        echo json_encode(["status" => "success", "message" => "Status updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();
