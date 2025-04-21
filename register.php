<?php
require 'config.php';
require 'vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $verify_token = md5(rand());

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, verify_token) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $verify_token);
    
    if ($stmt->execute()) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'DriveEasy');
        $mail->addAddress($email);
        $mail->Subject = "Verify your email";
        $mail->Body = "Click the link to verify: http://localhost/MIDTERM/verify.php?token=$verify_token";

        if ($mail->send()) {
            echo "Check your email to verify!";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "Error creating account.";
    }
}
?>
