<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // path to PHPMailer autoload

// Replace this with actual user data from your database
$validUser = "admin";
$validPass = "password123";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $validUser && $password === $validPass) {
        // Send Gmail notification
        $mail = new PHPMailer(true);

        try {
            // Gmail SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yourgmail@gmail.com'; // replace with sender Gmail
            $mail->Password   = 'your-app-password';   // use app password (not your Gmail password)
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Email content
            $mail->setFrom('yourgmail@gmail.com', 'Login Notifier');
            $mail->addAddress('20211395@nbsc.edu.ph');
            $mail->Subject = 'New Login Detected';
            $mail->Body    = "User '$username' has successfully logged in at " . date("Y-m-d H:i:s");

            $mail->send();
            echo "Login successful! Notification sent.";
        } catch (Exception $e) {
            echo "Login successful, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!-- Simple Login Form -->
<form method="post">
  <h2>Login</h2>
  <label>Username:</label>
  <input type="text" name="username" required><br><br>
  <label>Password:</label>
  <input type="password" name="password" required><br><br>
  <button type="submit">Login</button>
</form>
