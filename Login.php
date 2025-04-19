<?php
require 'config.php'; // DB and email config

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    }

    // Check user in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Send email on successful login
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom(SMTP_USER, 'Login Notifier');
                $mail->addAddress('20211395@nbsc.edu.ph');
                $mail->Subject = 'Login Alert';
                $mail->Body    = "User '$username' logged in at " . date("Y-m-d H:i:s");

                $mail->send();
                echo "Login successful. Notification sent!";
            } catch (Exception $e) {
                echo "Login successful, but email failed: {$mail->ErrorInfo}";
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML form -->
<form method="post">
  <h2>Login</h2>
  <label>Username:</label>
  <input type="text" name="username" required><br><br>
  <label>Password:</label>
  <input type="password" name="password" required><br><br>
  <button type="submit">Login</button>
</form>
