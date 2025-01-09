<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "job_portal";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session management
session_start();

// Routing
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
switch ($page) {
    case 'login':
        include 'pages/login.php';
        break;
    case 'register':
        include 'pages/register.php';
        break;
    case 'admin_dashboard':
        include 'pages/admin_dashboard.php';
        break;
    case 'user_dashboard':
        include 'pages/user_dashboard.php';
        break;
    case 'job_list':
        include 'pages/job_list.php';
        break;
    case 'job_apply':
        include 'pages/job_apply.php';
        break;
    case 'profile':
        include 'pages/profile.php';
        break;
    case 'logout':
        session_destroy();
        header("Location: index.php?page=login");
        break;
    case 'chat':
        include 'pages/chat.php';
        break;
    default:
        include 'pages/home.php';
        break;
}
?>

<!-- Basic layout structure -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="index.php?page=admin_dashboard">Dashboard</a></li>
                    <li><a href="index.php?page=job_list">Manage Jobs</a></li>
                    <li><a href="index.php?page=chat">Chat</a></li>
                    <li><a href="index.php?page=logout">Logout</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                    <li><a href="index.php?page=user_dashboard">Dashboard</a></li>
                    <li><a href="index.php?page=job_list">Jobs</a></li>
                    <li><a href="index.php?page=profile">Profile</a></li>
                    <li><a href="index.php?page=chat">Chat</a></li>
                    <li><a href="index.php?page=logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=home">Home</a></li>
                    <li><a href="index.php?page=login">Login</a></li>
                    <li><a href="index.php?page=register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <?php include($page . '.php'); ?>
    </main>
    <footer>
        <p>&copy; 2025 Job Portal</p>
    </footer>
</body>
</html>