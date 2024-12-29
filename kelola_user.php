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
    <title>Kelola User</title>
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
        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f9;
        }
        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
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
    <h2>Kelola User</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Username</th>
                
              
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($users) {
                foreach ($users as $user) {
                    echo "<tr>
                        <td>{$user['name']}</td>
                        <td>{$user['username']}</td>
                       
                        
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada pengguna terdaftar.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="dashboard.php"  class="Kembali">Kembali</a>
    <a href="registrasi.php" class="registrasi">Registrasi</a>
    <a href="logout.php"     class="logout">Logout</a>
    

</div>
</body>
</html>
