<?php
require 'config.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Verify the email and update the user
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
    $stmt->bind_param("s", $email);
    
    if ($stmt->execute()) {
        echo "Your email has been verified! You can now <a href='login.php'>login</a>";
    } else {
        echo "Error verifying email.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid verification link.";
}
