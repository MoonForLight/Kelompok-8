<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header("Location: ../front-end/login.php");
    exit;
}

$staff_id = $_SESSION['user']['id'];
$idol_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validasi relasi
$cek = mysqli_query($conn, "SELECT * FROM staff_idol WHERE staff_id=$staff_id AND idol_id=$idol_id");
if (mysqli_num_rows($cek) === 0) {
    die("Idol tidak valid.");
}

// Ambil daftar unik penggemar
$penggemar_result = mysqli_query($conn, "
    SELECT u.id, u.username, u.profile_pic
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.idol_id = $idol_id AND m.to_staff_id = $staff_id AND m.sender_role = 'penggemar'
    GROUP BY u.id
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inbox Staff</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            background: #000;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        .chat-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            background: #111;
            padding: 15px;
            border-radius: 12px;
        }
        .fan-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .fan-item {
            background: #1a1a1a;
            border-radius: 10px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: background 0.3s;
        }
        .fan-item:hover {
            background: #2a2a2a;
        }
        .fan-avatar {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid #444;
        }
        .fan-username {
            font-size: 16px;
            font-weight: 600;
        }
        .chat-back {
            display: inline-block;
            margin: 20px;
            font-size: 18px;
            color: #1d9bf0;
            text-decoration: none;
        }
    </style>
</head>
<body>
<a href="manage_idol.php?id=<?= $idol_id ?>" class="chat-back">← Kembali</a>
<div class="container">
    <div class="chat-header">Daftar Penggemar</div>
    <div class="fan-list">
        <?php while ($fan = mysqli_fetch_assoc($penggemar_result)): ?>
            <a href="inbox_staff_chat.php?id=<?= $idol_id ?>&fan_id=<?= $fan['id'] ?>" class="fan-item">
                <img src="../../assets/uploads/<?= $fan['profile_pic'] ?? 'default.png' ?>" class="fan-avatar">
                <div class="fan-username"><?= htmlspecialchars($fan['username']) ?></div>
            </a>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
