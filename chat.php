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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .chat-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
        }
        #chat-box {
            flex-grow: 1;
            padding: 10px;
            padding-top: 300px;
            overflow-y: auto;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        .chat-message {
            display: flex;
            margin: 10px 0;
        }
        .message-sender {
            justify-content: flex-end;
        }
        .message-receiver {
            justify-content: flex-start;
        }
        .chat-bubble {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            background: #007bff;
            color: white;
            word-wrap: break-word;
        }
        .message-receiver .chat-bubble {
            background: #e5e5ea;
            color: black;
        }
        form {
            display: flex;
            padding: 10px;
            background: #fff;
        }
        textarea {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            resize: none;
            margin-right: 10px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .back-link {
            text-align: center;
            margin: 10px 0;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            Chat dengan <?= htmlspecialchars($friend['name']) ?>
        </div>
        <div id="chat-box">
            <?php foreach ($messages as $message): ?>
                <div class="chat-message <?= $message['sender_id'] == $user['id'] ? 'message-sender' : 'message-receiver' ?>">
                    <div class="chat-bubble">
                        <?= htmlspecialchars($message['message']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST">
            <textarea name="message" placeholder="Tulis pesan..." rows="1" required></textarea>
            <button type="submit">Kirim</button>
        </form>
        <div class="back-link">
            <a href="dashboard.php">Kembali</a>
        </div>
    </div>
</body>
</html>
