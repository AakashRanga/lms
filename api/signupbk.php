<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

include "../includes/config.php";

try {

    $data = file_get_contents('php://input');
    $json_data = json_decode($data, true);

    $RequestMethod = $_SERVER["REQUEST_METHOD"];

    if ($RequestMethod !== "POST") {
        throw new Exception($RequestMethod . ' Method Not Allowed', 405);
    }

    $requiredFields = ['email', 'password', 'name', 'reg_no', 'user_type'];

    $missingFields = [];

    // Check for missing fields
    foreach ($requiredFields as $field) {
        if (!isset($json_data[$field]) || empty($json_data[$field])) {
            $missingFields[] = $field;  // Add the missing field to the array
        }
    }

    // If there are missing fields, throw an exception
    if (!empty($missingFields)) {
        $missingFieldsStr = implode(', ', $missingFields);
        throw new Exception('Missing required field(s): ' . $missingFieldsStr, 400);
    }

    $platform = 'web';

    // using web and api raw json
    $reg_no = isset($json_data['reg_no']) ? addslashes(trim($json_data['reg_no'])) : null;
    $email = isset($json_data['email']) ? addslashes(trim($json_data['email'])) : null;
    $password = isset($json_data['password']) ? addslashes(trim($json_data['password'])) : null;
    $name = isset($json_data['name']) ? addslashes(trim($json_data['name'])) : null;
    $user_type = isset($json_data['user_type']) ? addslashes(trim($json_data['user_type'])) : null;
    $mobile = isset($json_data['mobile']) ? addslashes(trim($json_data['mobile'])) : null;
    $department = isset($json_data['department']) ? addslashes(trim($json_data['department'])) : null;
    // using form API
    // $email = addslashes(trim($_REQUEST['email']));
    // $password = addslashes(trim($_REQUEST['password']));
    // $name = addslashes(trim($_REQUEST['name']));
    // $user_type = addslashes(trim($_REQUEST['user_type']));

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format', 400);
    }

    // Check if email is already registered
    $CheckEmailQuery = "SELECT * FROM lms_login WHERE email = '$email' and reg_no= '$reg_no' ";
    $CheckEmailResult = mysqli_query($conn, $CheckEmailQuery);

    if (!$CheckEmailResult) {
        throw new Exception('Database error: ' . mysqli_error($conn), 500);
    }

    if (mysqli_num_rows($CheckEmailResult) > 0) {
        // Email already exists
        throw new Exception('Email already registered', 409);
    }

    // Insert new user
    $InsertUserQuery = "INSERT INTO lms_login (email, password, reg_no , name, mobile, department, user_type, created_at) VALUES ('$email', '$password', '$reg_no', '$name', '$mobile', '$department', '$user_type', now())";
    if (!mysqli_query($conn, $InsertUserQuery)) {
        throw new Exception('Error registering user: ' . mysqli_error($conn), 500);
    }

    // Success response
    $Data = [
        'status' => 201,
        'message' => 'User successfully registered'
    ];
    header("HTTP/1.0 201 Created");
    echo json_encode($Data);
} catch (Exception $e) {
    // Handle all errors in the catch block
    $Data = [
        'status' => $e->getCode() ?: 500,
        'message' => $e->getMessage()
    ];
    header("HTTP/1.0 " . ($e->getCode() ?: 500) . " Error");
    echo json_encode($Data);
}
