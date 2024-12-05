<?php
// Database connection details
$servername = "localhost";  // For WAMP, this is usually 'localhost'
$db_username = "root";      // Default MySQL username for WAMP is 'root'
$db_password = "";          // Default WAMP password is empty (no password)
$dbname = "test_login";  // Replace 'your_database' with the actual name of your database

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
