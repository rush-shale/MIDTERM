<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['rush-shale'];
    $password = $_POST['262001'];

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            if ($user['verified'] == 1) {
                echo "Login successful!";
                // Redirect to dashboard or other page
            } else {
                echo "Your email is not verified. Please check your inbox.";
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

<form method="post">
    <h2>Login</h2>
    <label>Username:</label>
    <input type="text" name="username" required><br><br>
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
