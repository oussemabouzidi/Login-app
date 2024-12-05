<?php
require_once 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


function sendemail_verify($username, $email, $token)
{
    // Send confirmation email with the token
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Set the SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'bouzidioussema16@gmail.com';  // SMTP username
        $mail->Password = 'dpah sbwl prit ganv
';           // SMTP password
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        // Recipient
        $mail->setFrom('bouzidioussema16@gmail.com', $username);
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Confirmation';

        $email_template = "
            <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px;'>
            <div style='text-align: center;'>
                <img src='img/Logo_ISET_Kélibia.jpg' alt='ISET Kelibia' style='max-width: 100px;'>
                <h2 style='color: #333333;'>Bienvenue à ISET Kelibia</h2>
            </div>
            
            <hr style='border: 1px solid #eeeeee; margin: 20px 0;'>

            <p style='font-size: 16px; color: #333333;'>Bonjour <strong>$username</strong>,</p>
            <p style='font-size: 16px; color: #333333;'>Merci de vous être inscrit(e) sur la plateforme de gestion des stages à ISET Kelibia. Veuillez vérifier votre adresse e-mail en cliquant sur le lien ci-dessous :</p>

            <div style='text-align: center; margin: 30px 0;'>
                <a href='http://localhost/test_identification/confirm.php?token=$token' style='background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;'>Confirmer votre e-mail</a>
            </div>

            <p style='font-size: 14px; color: #666666;'>Si vous n'avez pas demandé cette vérification, veuillez ignorer cet e-mail.</p>
            
            <hr style='border: 1px solid #eeeeee; margin: 20px 0;'>

            <p style='font-size: 12px; color: #999999; text-align: center;'>© ISET Kelibia 2024. Tous droits réservés.</p>
        </div>
    </div>
        ";
        $mail->Body = $email_template;

        $mail->send();
        echo 'Confirmation email has been sent!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = trim($_POST['newusername'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['newpassword'] ?? '');
    $confirm_password = trim($_POST['newconfirm_password'] ?? '');

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate a unique token for email confirmation
    $token = bin2hex(random_bytes(50));

    // Validate that passwords match
    if ($password != $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }


    // Check if the email is already registered
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($check_email_sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "Email is already registered!";
            exit;
        }
        $stmt->close();
    }

    // Prepare SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password, token) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $token);
        sendemail_verify($username, $email, $token);
        if ($stmt->execute()) {
            echo "<br/>Registration successful! Please check your email to confirm.";
        } else {
            echo "Something went wrong!";
        }
        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement.";
    }

    $conn->close();
}
