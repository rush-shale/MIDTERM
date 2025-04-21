<?php
require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $stmt = $conn->prepare("SELECT * FROM users WHERE verify_token=? AND verified=0 LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $update = $conn->prepare("UPDATE users SET verified=1, verify_token=NULL WHERE id=?");
        $update->bind_param("i", $user['id']);
        $update->execute();
        echo "Email verified! You can now <a href='index.html'>login</a>.";
    } else {
        echo "Invalid or already used verification link.";
    }
} else {
    echo "No token provided.";
}
?>
