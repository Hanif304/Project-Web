<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: kelola_user.php');
    exit;
}

$stmt = $conn->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-bottom: 20px;
        }
        button {
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<h2>Kelola User</h2>
<h3>Daftar Pengguna</h3>
<table>
    <tr>
        <th>Nama</th>
        <th>Username</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <form method="POST">
            <td><input type="text" name="name" value="<?= $u['name'] ?>" required></td>
	    <td><input type="text" name="username" value="<?= $u['username'] ?>" required></td>
            <td>
                <input type="hidden" name="id" value="<?= $u['id'] ?>"> 
                <button type="submit" name="delete_user">Hapus</button>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>
<a href="dashboard.php">Kembali</a> 
</body>
</html>
