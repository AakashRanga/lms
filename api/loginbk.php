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

    $email    = isset($json_data['email']) ? addslashes(trim($json_data['email'])) : null;
    $password = isset($json_data['password']) ? addslashes(trim($json_data['password'])) : null;

    $missingFields = [];
    if (empty($email)) $missingFields[] = 'Email';
    if (empty($password)) $missingFields[] = 'Password';

    if (!empty($missingFields)) {
        throw new Exception('Missing Field(s): ' . implode(', ', $missingFields), 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format', 400);
    }

    $platform    = isset($json_data['platform']) ? addslashes(trim($json_data['platform'])) : 'web';

    // Query to check user existence and their admin status
    $CheckUserQuery = "SELECT * FROM lms_login WHERE email = '$email' AND password = '$password'";
    $CheckUserQueryResults = mysqli_query($conn, $CheckUserQuery);

    if (!$CheckUserQueryResults) {
        throw new Exception('Database query failed', 500);
    }

    if (mysqli_num_rows($CheckUserQueryResults) > 0) {
        $record = mysqli_fetch_assoc($CheckUserQueryResults);

        // Check admin_status Pending
        if ($record['admin_status'] == 'pending') {
            // If admin_status is not 1, send a message to wait for approval
            $Data = [
                'status' => 403, // Forbidden (waiting for approval)
                'message' => 'Please wait for admin approval'
            ];
            echo json_encode($Data);
            exit; // Stop further execution
        }
        // Check admin_status Suspend
        if ($record['admin_status'] == 'blocked') {
            $Data = [
                'status' => 403, // Forbidden (waiting for approval)
                'message' => 'Your Account Has Been Suspended, Please Contact Admin'
            ];
            echo json_encode($Data);
            exit; 
        }

        // Determine account type
        $AccountType = "";
        if ($record["user_type"] == "Admin") {
            $AccountType = "Admin";
        } elseif ($record["user_type"] == "Student") {
            $AccountType = "Student";
        } elseif ($record["user_type"] == "Faculty") {
            $AccountType = "Faculty";
        }

        // Handle session and response for web platform
        if ($platform === "web") {
            $_SESSION["user_logged_in"] = true;
            $_SESSION["userid"] = $record["u_id"];
            $_SESSION["name"] = $record["name"];
            $_SESSION["email"] = $record["email"];

            $Data = [
                'status' => 200,
                'message' => 'Success',
                'user_type' => $AccountType,
                'user_name' => $_SESSION["name"],
                'user_id' => $_SESSION["userid"]
            ];
            header("HTTP/1.0 200 Success");
            echo json_encode($Data);
        } else {
            $Data = [
                'status' => 200,
                'message' => 'Login Success',
                'user_logged_in' => 'true',
                'user_id' => $record["u_id"],
                'user_name' => $record["name"],
                'user_email' => $record["email"],
                'user_type' => $AccountType
            ];
            header("HTTP/1.0 200 Success");
            echo json_encode($Data);
        }
    } else {
        $Data = [
            'status' => 401, // Unauthorized (invalid email or password)
            'message' => 'Invalid email or password'
        ];
        echo json_encode($Data);
    }
} catch (Exception $e) {
    $status = $e->getCode() ? $e->getCode() : 500; // Default to 500 if no code is set
    $message = $e->getMessage();

    $Data = [
        'status' => $status,
        'message' => $message
    ];
    header("HTTP/1.0 $status " . getStatusCodeMessage($status));
    echo json_encode($Data);
}

function getStatusCodeMessage($status)
{
    $codes = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Invalid Email And Password',
        403 => 'Forbidden: Admin Approval Required',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error'
    ];
    return isset($codes[$status]) ? $codes[$status] : 'Unknown Status';
}
?>
