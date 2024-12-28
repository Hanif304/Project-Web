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
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007BFF;
        }
        h3 {
            color: #333;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #ff4d4d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s;
        }
        .logout:hover {
            background: #cc0000;
        }
        .welcome-message {
            text-align: center;
            margin-top: 10px;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($user['role'] == 'admin'): ?>
        <h2>Dashboard Admin</h2>
        <h3>Daftar Pengguna</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM users");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($users) {
                    foreach ($users as $u) {
                        echo "
                        <tr>
                            <td>{$u['name']}</td>
                            <td>{$u['username']}</td>
                            <td><button onclick=\"location.href='chat.php?user_id={$u['id']}'\">Chat</button></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Tidak ada pengguna terdaftar.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>Dashboard User</h2>
        <p class="welcome-message">Selamat datang, <strong><?= htmlspecialchars($user['name']) ?></strong>!</p>
        <h3>Daftar Teman</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Aktivitas Anda</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM users WHERE id != ?");
                $stmt->execute([$user['id']]);
                $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($friends) {
                    foreach ($friends as $friend) {
                        echo "
                        <tr>
                            <td>{$friend['username']}</td>
                            <td><button onclick=\"location.href='chat.php?user_id={$friend['id']}'\">Chat</button></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Tidak ada teman tersedia.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>
