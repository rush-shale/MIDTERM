<?php
require 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Username or email already exists!";
    } else {
        // Insert new user (with verification flag set to 0)
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, verified) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $username, $password, $email);
        if ($stmt->execute()) {
            // Send verification email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASS;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom(SMTP_USER, 'No Reply');
                $mail->addAddress($email);
                $mail->Subject = 'Email Verification';
                $mail->Body = 'Click the link below to verify your email:
                http://localhost/your-project-folder/verify.php?email=' . $email;

                $mail->send();
                echo "Registration successful! Please check your email for verification.";
            } catch (Exception $e) {
                echo "Error: " . $mail->ErrorInfo;
            }
        } else {
            echo "Error registering user!";
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<form method="post">
    <h2>Sign Up</h2>
    <label>Username:</label>
    <input type="text" name="username" required><br><br>
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    <label>Email:</label>
    <input type="email" name="email" required><br><br>
    <button type="submit">Sign Up</button>
</form>
