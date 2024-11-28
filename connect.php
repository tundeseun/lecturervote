<?php
// Database connection using MySQLi
$host = 'localhost';
$dbname = 'pgcollege_new';
$username = 'root';
$password = '';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
