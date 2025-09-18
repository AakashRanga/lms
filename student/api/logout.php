<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

session_start();

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception($_SERVER["REQUEST_METHOD"] . ' Method Not Allowed', 405);
    }

    // Destroy all session data
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    $Data = [
        "status" => 200,
        "message" => "Logout successful"
    ];
    header("HTTP/1.0 200 Success");
    echo json_encode($Data);

} catch (Exception $e) {
    $status = $e->getCode() ?: 500;
    $message = $e->getMessage();

    $Data = [
        "status" => $status,
        "message" => $message
    ];
    header("HTTP/1.0 $status " . getStatusCodeMessage($status));
    echo json_encode($Data);
}

function getStatusCodeMessage($status)
{
    $codes = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error'
    ];
    return isset($codes[$status]) ? $codes[$status] : 'Unknown Status';
}
?>
