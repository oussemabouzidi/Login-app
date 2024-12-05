<?php
session_start();
require 'db_connection.php'; // Ensure you have a file to connect to your database

// Initialize variables
$codeCorrect = false;
$errorMessage = '';
$passwordUpdated = false;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // First, check if the reset code is provided or if the code was already verified (using hidden input)
    if (isset($_POST['reset_code']) || isset($_POST['code_verified'])) {
        if (isset($_POST['reset_code'])) {
            $enteredCode = $_POST['reset_code'];

            // Check if the entered code matches the code stored in the session
            if (isset($_SESSION['reset_code']) && $enteredCode == $_SESSION['reset_code']) {
                $codeCorrect = true; // Code is correct
            } else {
                $errorMessage = "Le code que vous avez entré est incorrect.";
            }
        } elseif (isset($_POST['code_verified']) && $_POST['code_verified'] == '1') {
            $codeCorrect = true; // The code was already verified
        }

        // If the code is correct and the new password is provided
        if ($codeCorrect && isset($_POST['new_password'])) {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            $email = $_SESSION['email']; // Get the email from the session

            // Check if the new password and confirmation match
            if ($newPassword !== $confirmPassword) {
                $errorMessage = "Les mots de passe ne correspondent pas.";
            } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)) {
                $errorMessage = "Le mot de passe doit comporter au moins 8 caractères, y compris au moins un chiffre et une lettre!";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password in the database
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param('ss', $hashedPassword, $email);
                if ($stmt->execute()) {
                    $passwordUpdated = true; // Password updated successfully
                    // Clear the session
                    session_unset();
                    session_destroy();

                    echo '<script type="text/javascript">
            alert("Votre mot de passe a été mis à jour avec succès.");
            window.location.href = "index.html";
          </script>';

                    exit();
                } else {
                    $errorMessage = "Erreur lors de la mise à jour du mot de passe.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du Code</title>
    <link rel="stylesheet" href="styles.css?v=1.0">
</head>

<body>
    <h2>Gestion des Stages Étudiants - Vérification du Code</h2>
    <div class="container" id="container">
        <div class="form-container reset-password-container">
            <?php if ($passwordUpdated): ?>
                <h1>Mot de Passe Réinitialisé</h1>
                <p>Votre mot de passe a été mis à jour avec succès.</p>
            <?php elseif ($codeCorrect): ?>
                <h1 style="padding:25px">Code Vérifié</h1>
                <form action="verify_code.php" method="post">
                    <input type="hidden" name="code_verified" value="1" /> <!-- Hidden input to mark code as verified -->
                    <input type="password" placeholder="Nouveau Mot de Passe" name="new_password" required />
                    <input type="password" placeholder="Confirmez le Mot de Passe" name="confirm_password" required />
                    <?php if ($errorMessage != ''): ?>
                        <p>
                            <?php echo $errorMessage; ?>
                        </p>
                    <?php endif; ?>
                    <button type="submit">Réinitialiser le Mot de Passe</button>
                </form>
            <?php else: ?>
                <h1>Vérification du Code</h1>
                <form action="verify_code.php" method="post">
                    <input type="text" placeholder="Code de Réinitialisation" name="reset_code" required />
                    <button type="submit">Vérifier le Code</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script src="app.js"></script>
</body>

</html>