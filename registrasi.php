<?php
include 'koneksi.php';

// Query untuk menambahkan kolom email dan alamat jika belum ada
try {
    $conn->exec("ALTER TABLE users ADD COLUMN email VARCHAR(255) AFTER username, ADD COLUMN address TEXT AFTER email");
} catch (Exception $e) {
    // Kolom sudah ada, abaikan error
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password']; // Password disimpan apa adanya
    $role = 'user';

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, address, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $username, $email, $address, $password, $role]);

    // Redirect ke index.php setelah registrasi berhasil
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input, textarea {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        textarea {
            height: 60px;
            resize: none;
        }
        button {
            width: 95%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrasi</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Nama" required><br>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <textarea name="address" placeholder="Alamat" required></textarea><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Daftar</button>
        </form>
        <a href="index.php" class="link">Sudah Punya Akun? Login</a>
    </div>
</body>
</html>
