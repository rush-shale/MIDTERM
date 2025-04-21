<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="dashboard-box">
        <h1>Welcome to this page, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p>You have successfully logged in.</p>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
