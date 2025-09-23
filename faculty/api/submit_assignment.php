<?php
error_reporting(0);

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept");

include "../../includes/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => 405, "message" => "Invalid Request Method"]);
    exit();
}

try {
    session_start();

    $faculty_id = $_SESSION['userid'] ?? '';
    $faculty_name = $_SESSION['name'] ?? '';

    if (!$faculty_id || !$faculty_name) {
        http_response_code(401);
        echo json_encode(["status" => 401, "message" => "Unauthorized: Faculty not logged in"]);
        exit();
    }

    // Only launch_c_id is passed
    $c_id = 0; // will be fetched later from launch_courses
    $launch_c_id = intval($_POST['launch_c_id'] ?? ($_GET['launch_c_id'] ?? 0));
    $title = trim($_POST['title'] ?? '');
    $instruction = !empty($_POST['instruction']) ? trim($_POST['instruction']) : null;
    $marks = trim($_POST['marks'] ?? '');
    $due_date = trim($_POST['due_date'] ?? '');

    // Validate required fields
    $missing = [];
    if (!$launch_c_id)
        $missing[] = "launch_c_id";
    if (!$title)
        $missing[] = "title";
    if (!$marks)
        $missing[] = "marks";
    if (!$due_date)
        $missing[] = "due_date";

    if (!empty($missing)) {
        http_response_code(400);
        echo json_encode([
            "status" => 400,
            "message" => "Missing fields: " . implode(", ", $missing)
        ]);
        exit();
    }

    // Fetch c_id based on launch_c_id
    $courseQuery = $conn->prepare("SELECT c_id FROM launch_courses WHERE id = ?");
    $courseQuery->bind_param("i", $launch_c_id);
    $courseQuery->execute();
    $courseResult = $courseQuery->get_result();

    if ($courseResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["status" => 404, "message" => "No course found for given launch_c_id"]);
        exit();
    }

    $row = $courseResult->fetch_assoc();
    $c_id = intval($row['c_id']);
    $courseQuery->close();

    // File upload
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
        http_response_code(400);
        echo json_encode(["status" => 400, "message" => "Please upload a PDF file"]);
        exit();
    }

    $allowed = ['pdf'];
    $fileName = basename($_FILES['file']['name']);
    $fileTmp = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        http_response_code(400);
        echo json_encode(["status" => 400, "message" => "Only PDF files are allowed"]);
        exit();
    }

    $newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName);
    $uploadDir = "../uploads/assignments/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . $newFileName;
    if (!move_uploaded_file($fileTmp, $filePath)) {
        http_response_code(500);
        echo json_encode(["status" => 500, "message" => "Failed to upload file"]);
        exit();
    }

    $dbFilePath = "uploads/assignments/" . $newFileName;

    // Insert into DB (make sure assignments table has launch_c_id column!)
    $stmt = $conn->prepare("INSERT INTO assignment
        (c_id, launch_id, faculty_id, title, instruction, notes, marks, due_date, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "iiisssss",
        $c_id,
        $launch_c_id,
        $faculty_id,
        $title,
        $instruction,
        $dbFilePath,
        $marks,
        $due_date
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => 200, "message" => "Assignment submitted successfully"]);
    } else {
        throw new Exception("DB insertion failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => 500, "message" => $e->getMessage()]);
}
