<?php
require 'config.php';
session_start();

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            if ($user['verified'] == 1) {
                $_SESSION['user'] = $user;
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Please verify your email.";
            }
        } else {
            echo "Wrong password.";
        }
    } else {
        echo "User not found.";
    }
}
?>
