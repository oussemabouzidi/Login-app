<?php
session_start();
require 'vendor/autoload.php'; // Make sure this path is correct

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize variables
$emailSent = false;
$errorMessage = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Generate a random 6-digit code
    $code = rand(100000, 999999);

    // Store the code and email in the session
    $_SESSION['reset_code'] = $code;
    $_SESSION['email'] = $email; // Store the email for later use

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bouzidioussema16@gmail.com';
        $mail->Password = 'dpah sbwl prit ganv
';
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('bouzidioussema16@gmail.com', 'Admin');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body    = "Votre code de réinitialisation est : <strong>$code</strong>";

        // Send the email
        $mail->send();
        $emailSent = true; // Email sent successfully
    } catch (Exception $e) {
        $errorMessage = "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du Mot de Passe</title>
    <link rel="stylesheet" href="styles.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <h2>Gestion des Stages Étudiants - Réinitialisation du Mot de Passe</h2>
    <div class="container" id="container">
        <div class="form-container reset-password-container">
            <?php if ($emailSent): ?>
                <h1>Code de Réinitialisation Envoyé</h1>
                <span>Un code de réinitialisation a été envoyé à votre email. Entrez le code ci-dessous :</span>
                <form action="verify_code.php" method="post">
                    <input type="text" placeholder="Code de Réinitialisation" id="reset_code" name="reset_code" required />
                    <button>Vérifier le Code</button>
                </form>
            <?php elseif ($errorMessage): ?>
                <h1>Erreur</h1>
                <p><?php echo $errorMessage; ?></p>
                <a href="forgot_password.html">Réessayer</a>
            <?php else: ?>
                <h1>Réinitialisation du Mot de Passe</h1>
                <span>Entrez votre adresse e-mail pour recevoir un code de réinitialisation.</span>
                <form action="reset_request.php" method="post">
                    <input type="email" placeholder="Email" id="email" name="email" required />
                    <button>Envoyer le code de réinitialisation</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script src="app.js"></script>
</body>

</html>