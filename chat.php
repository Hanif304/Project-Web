<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

if (!isset($_GET['user_id'])) {
    echo "No user selected for chat.";
    exit;
}

$receiver_id = $_GET['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$receiver_id]);
$friend = $stmt->fetch();

if (!$friend) {
    echo "Teman tidak ditemukan.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM chats WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC");
$stmt->execute([$user['id'], $receiver_id, $receiver_id, $user['id']]);
$messages = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO chats (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $receiver_id, $message]);

    header("Location: chat.php?user_id=$receiver_id");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?= htmlspecialchars($friend['name']) ?></title>
    <style>
        #chat-box {
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
        #chat-box p {
            padding: 5px;
            margin: 5px 0;
        }
        .message-sender {
            color: blue;
        }
        .message-receiver {
            color: green;
        }
    </style>
</head>
<body>
    <h2>Chat dengan <?= htmlspecialchars($friend['name']) ?></h2>
    
    <div id="chat-box">
        <?php foreach ($messages as $message): ?>
            <p class="<?= $message['sender_id'] == $user['id'] ? 'message-sender' : 'message-receiver' ?>">
                <strong><?= $message['sender_id'] == $user['id'] ? 'Anda' : $friend['name'] ?>:</strong>
                <?= htmlspecialchars($message['message']) ?>
            </p>
        <?php endforeach; ?>
    </div>
    
    <form method="POST">
        <textarea name="message" placeholder="Tulis pesan..." required></textarea><br>
        <button type="submit">Kirim</button>
    </form>

    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
