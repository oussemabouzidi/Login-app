<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query to check username and if the user is verified
    $sql = "SELECT * FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($user['is_verified'] == 0) {
                echo "Please verify your email before logging in.";
                exit;
            }

            // Verify the password
            if (password_verify($password, $user['password'])) {
                echo "Login successful!";
            } else {
                echo "Invalid username or password.";
            }
        } else {
            echo "Invalid username or password.";
        }

        $stmt->close();
    }
}

$conn->close();
