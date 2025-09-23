<?php
session_start();


if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $Protocol = "https://";
} else {
    $Protocol = "http://";
}

define("SITE_URL", $Protocol . $_SERVER['SERVER_NAME']);
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . "");
define("UPLOAD_PATH", $_SERVER['DOCUMENT_ROOT'] . "/uploads");
// define("THUMBNAIL_URL", $_SERVER['DOCUMENT_ROOT']."/workingproject/lms/faculty/uploads/course_materials/thumbnail");


define("SECRET_KEY", "my_super_secret_key_123");

define("THUMBNAIL_URL", SITE_URL . "/workingproject/lms/faculty/uploads/course_materials/thumbnail");
define("UPLOAD_URL", SITE_URL . "/assets/uploads");

define("PROFILE_UPLOAD_PATH", $_SERVER['DOCUMENT_ROOT'] . "/assets/uploads/profiles");


define("PROFILE", UPLOAD_URL . "/profiles/");
define("COURSES", UPLOAD_URL . "/courses/");

// Database configuration
$host = "localhost";     // Database host (use your DB host)
$username = "root";          // Database username
$password = "12345";               // Database password
// $password = "";
$database = "lms";    // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "status" => 500,
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}
