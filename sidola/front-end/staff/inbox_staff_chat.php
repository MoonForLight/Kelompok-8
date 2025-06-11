<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header("Location: ../front-end/login.php");
    exit;
}

$staff_id = $_SESSION['user']['id'];
$idol_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$fan_id = isset($_GET['fan_id']) ? intval($_GET['fan_id']) : 0;

// Validasi relasi staff-idol
$cek = mysqli_query($conn, "SELECT * FROM staff_idol WHERE staff_id=$staff_id AND idol_id=$idol_id");
if (!$cek || mysqli_num_rows($cek) === 0) {
    die("Akses tidak sah.");
}

// Ambil data penggemar
$fan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$fan_id AND role='penggemar'"));

// Kirim balasan dari staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    mysqli_query($conn, "INSERT INTO messages (sender_id, receiver_id, idol_id, to_staff_id, sender_role, message, created_at)
                         VALUES ($staff_id, $fan_id, $idol_id, $staff_id, 'staff', '$msg', NOW())");
}

// Ambil semua pesan 2 arah (penggemar ke staff & staff ke penggemar)
$chat = mysqli_query($conn, "
    SELECT * FROM messages
    WHERE idol_id=$idol_id AND to_staff_id=$staff_id 
      AND ((sender_id=$fan_id AND sender_role='penggemar') 
           OR (sender_id=$staff_id AND receiver_id=$fan_id AND sender_role='staff'))
    ORDER BY created_at ASC
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chat dengan <?= htmlspecialchars($fan['username']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="chat-dark">
<div class="chat-wrapper">
    <div class="chat-container">
        <div class="chat-header">
            <a href="inbox_staff.php?id=<?= $idol_id ?>" class="chat-back">←</a>
            <img src="../../assets/uploads/<?= $fan['profile_pic'] ?? 'default.png' ?>" class="chat-idol-pic">
            <span class="chat-idol-name"><?= htmlspecialchars($fan['username']) ?></span>
        </div>

        <div class="chat-messages" id="messageArea">
            <?php while ($row = mysqli_fetch_assoc($chat)): ?>
                <div class="chat-bubble <?= $row['sender_role'] === 'staff' ? 'right' : 'left' ?>">
                    <?= htmlspecialchars($row['message']) ?>
                    <div class="chat-time"><?= date("H:i", strtotime($row['created_at'])) ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <form method="POST" class="chat-form">
            <input type="text" name="message" placeholder="Tulis balasan..." required autocomplete="off">
            <button type="submit">➤</button>
        </form>
    </div>
</div>
</body>
</html>
