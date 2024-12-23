<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #333;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            padding: 8px 0;
        }
        .logout {
            display: inline-block;
            margin-top: 10px;
            padding: 10px;
            background: #ff4d4d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($user['role'] == 'admin'): ?>
        <h2>Dashboard Admin</h2>
        <a href="kelola_user.php">Kelola User</a><br><br>
        <h3>Daftar Pengguna:</h3>
        <ul>
            <?php
            $stmt = $conn->query("SELECT * FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($users) {
                foreach ($users as $u) {
                    echo "<li><a href='chat.php?user_id={$u['id']}'>{$u['name']}</a></li>";
                }
            } else {
                echo "<li>Tidak ada pengguna terdaftar.</li>";
            }
            ?>
        </ul>
    <?php else: ?>
        <h2>Dashboard User</h2>
        <p>Selamat datang, <?= $user['name'] ?>!</p>
        <h3>Daftar Teman:</h3>
        <ul>
            <?php
            $stmt = $conn->prepare("SELECT * FROM users WHERE id != ?");
            $stmt->execute([$user['id']]);
            $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($friends) {
                foreach ($friends as $friend) {
                    echo "<li><a href='chat.php?user_id={$friend['id']}'>{$friend['name']}</a></li>";
                }
            } else {
                echo "<li>Tidak ada teman tersedia.</li>";
            }
            ?>
        </ul>
    <?php endif; ?>

    <a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>
