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
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            background: linear-gradient(135deg, #1d3557, #457b9d);
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }

        .dashboard-box {
            background: #fff;
            color: #1d3557;
            max-width: 400px;
            margin: auto;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        a.logout {
            display: inline-block;
            margin-top: 20px;
            color: #e63946;
            font-weight: bold;
            text-decoration: none;
        }

        a.logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="dashboard-box">
        <h1>Welcome to this page, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p>You have successfully logged in.</p>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
