<?php
session_start();
// Database configuration
$host = "localhost";     // Database host (use your DB host)
$username = "root";          // Database username
// $password = "12345";               // Database password
$password = "";
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
