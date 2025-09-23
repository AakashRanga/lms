<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

include "../includes/config.php";

try {
    $u_id = $_SESSION['userid'] ?? null;

    if (!$u_id) {
        throw new Exception('User not logged in', 401);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception($_SERVER['REQUEST_METHOD'] . ' Method Not Allowed', 405);
    }

    // Get POST data
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : null;
    $department = isset($_POST['department']) ? trim($_POST['department']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Validate required fields
    $missingFields = [];
    if (empty($name))
        $missingFields[] = 'name';
    if (empty($email))
        $missingFields[] = 'email';
    if (empty($mobile))
        $missingFields[] = 'mobile';
    if (empty($department))
        $missingFields[] = 'department';

    if (!empty($missingFields)) {
        throw new Exception('Missing Field(s): ' . implode(', ', $missingFields), 400);
    }

    // Prepare query
    if (!empty($password)) {
        // Store password as plain text (NOT recommended for security)
        $query = "UPDATE lms_login SET name=?, email=?, mobile=?, department=?, password=? WHERE u_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $name, $email, $mobile, $department, $password, $u_id);
    } else {
        $query = "UPDATE lms_login SET name=?, email=?, mobile=?, department=? WHERE u_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $name, $email, $mobile, $department, $u_id);
    }


    if ($stmt->execute()) {
        $response = [
            'status' => 200,
            'message' => 'Profile updated successfully',
            'user' => [
                'u_id' => $u_id,
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'department' => $department
            ]
        ];
        echo json_encode($response);
    } else {
        throw new Exception('Failed to update profile', 500);
    }

} catch (Exception $e) {
    $status = $e->getCode() ? $e->getCode() : 500;
    $message = $e->getMessage();
    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
}
?>