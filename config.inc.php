<?php
// Database Configuration

session_start();
$db_server = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "event_management";

// Connect to the database
$conn = new mysqli($db_server, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
