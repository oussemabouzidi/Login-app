<?php
require_once 'db_connection.php';

// Get the token from the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists
    $sql = "SELECT * FROM users WHERE token = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Token found, update user as verified
            $user = $result->fetch_assoc();
            $update_sql = "UPDATE users SET is_verified = 1, token = NULL WHERE token = ?";

            if ($update_stmt = $conn->prepare($update_sql)) {
                $update_stmt->bind_param("s", $token);
                if ($update_stmt->execute()) {
                    echo "Email confirmed successfully!";
                } else {
                    echo "Failed to confirm email!";
                }
            }
        } else {
            echo "Invalid token!";
        }

        $stmt->close();
    }
}

$conn->close();
