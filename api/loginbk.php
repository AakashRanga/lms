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

    $platform = isset($json_data['platform']) ? addslashes(trim($json_data['platform'])) : 'web';

    // ✅ Verify user from lms_login
    $CheckUserQuery = "SELECT * FROM lms_login WHERE reg_no = '$email' AND password = '$password'";
    $CheckUserQueryResults = mysqli_query($conn, $CheckUserQuery);

    if (!$CheckUserQueryResults) {
        throw new Exception('Database query failed', 500);
    }

    if (mysqli_num_rows($CheckUserQueryResults) > 0) {
        $record = mysqli_fetch_assoc($CheckUserQueryResults);

        // Blocked or pending checks
        if ($record['admin_status'] == 'pending') {
            echo json_encode(['status' => 403, 'message' => 'Please wait for admin approval']);
            exit;
        }
        if ($record['admin_status'] == 'blocked') {
            echo json_encode(['status' => 403, 'message' => 'Your Account Has Been Suspended, Please Contact Admin']);
            exit;
        }

        // User role detection
        $AccountType = $record["user_type"];

        // ✅ Student specific logic
        if ($AccountType === "Student") {
            $studentId = $record["u_id"];      // primary key in lms_login
            $studentReg = $record["reg_no"];   // register number

            // Check if student has an approved course
            $CheckApproval = $conn->prepare("SELECT * FROM student_course_approval WHERE student_reg_no = ? AND status = 'approved' LIMIT 1");
            $CheckApproval->bind_param("s", $studentId);
            $CheckApproval->execute();
            $CheckApproval->store_result();

            $redirectPage = $CheckApproval->num_rows > 0 ? "student/mycourses.php" : "student/student_reg.php";
            $CheckApproval->close();
        } 
        elseif ($AccountType === "Admin") {
            $redirectPage = "admin/add_courses.php";
        } 
        elseif ($AccountType === "Faculty") {
            $redirectPage = "faculty/active-course.php";
        } else {
            $redirectPage = "";
        }

        // ✅ Prepare response
        if ($platform === "web") {
            $_SESSION["user_logged_in"] = true;
            $_SESSION["userid"]  = $record["u_id"];
            $_SESSION["name"]    = $record["name"];
            $_SESSION["email"]   = $record["email"];
            $_SESSION["user_type"] = $AccountType; 

            $Data = [
                'status'     => 200,
                'message'    => 'Success',
                'user_type'  => $AccountType,
                'user_name'  => $_SESSION["name"],
                'user_id'    => $_SESSION["userid"],
                'redirect'   => $redirectPage // ✅ tell frontend where to go
            ];
            echo json_encode($Data);
        } else {
            $Data = [
                'status'     => 200,
                'message'    => 'Login Success',
                'user_logged_in' => 'true',
                'user_id'    => $record["u_id"],
                'user_name'  => $record["name"],
                'user_email' => $record["email"],
                'user_type'  => $AccountType,
                'redirect'   => $redirectPage
            ];
            echo json_encode($Data);
        }

    } else {
        echo json_encode(['status' => 401, 'message' => 'Invalid email or password']);
    }

} catch (Exception $e) {
    $status = $e->getCode() ? $e->getCode() : 500;
    $message = $e->getMessage();
    echo json_encode(['status' => $status, 'message' => $message]);
}
?>
