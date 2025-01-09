<?php
session_start();
include 'koneksi.php'; // Menghubungkan ke file koneksi database

$error = ''; // Menyiapkan variabel untuk menampilkan pesan error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']); // Menghilangkan spasi di awal dan akhir input
    $password = md5($_POST['password']); // Memproses password dengan MD5 (disarankan untuk menggunakan hashing yang lebih aman seperti bcrypt, tetapi ini tetap dipertahankan untuk kebutuhan contoh)

    try {
        // Persiapkan query untuk memeriksa username dan password di database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]); // Menjalankan query dengan parameter username dan password
        $user = $stmt->fetch(); // Mengambil hasil pencarian user

        // Jika ditemukan user
        if ($user) {
            $_SESSION['user'] = $user; // Menyimpan informasi user ke sesi
            header('Location: dashboard.php'); // Mengarahkan ke halaman dashboard
            exit;
        } else {
            $error = "Username atau password salah!"; // Menampilkan pesan error jika login gagal
        }
    } catch (PDOException $e) {
        // Menangani error pada koneksi database
        $error = "Terjadi kesalahan pada server.";
        error_log("Database error: " . $e->getMessage()); // Menyimpan error log
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #0056b3;
        }
        .link {
            margin-top: 15px;
            display: block;
            color: #007bff;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- Form login dengan autocomplete="new-password" dan nama input unik -->
        <form method="POST" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required autocomplete="off" id="username"><br>
            <input type="password" name="password" placeholder="Password" required autocomplete="new-password" id="password"><br>
            <button type="submit">Login</button>
        </form>
        <a href="registrasi.php" class="link">Belum punya akun? Registrasi di sini</a>
    </div>
</body>
</html>
