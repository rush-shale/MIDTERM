<?php
require 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $conn->prepare("UPDATE users SET verified=1 WHERE verify_token=?");
    $stmt->bind_param("s", $token);
    if ($stmt->execute()) {
        echo "Email verified! You can now login.";
    } else {
        echo "Invalid verification link.";
    }
}
?>
