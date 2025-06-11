<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'penggemar') {
    header("Location: login.php");
    exit;
}

$penggemar_id = $_SESSION['user']['id'];
$idol_id = intval($_GET['id']);

// Ambil staff yang mengelola idol
$q = mysqli_query($conn, "SELECT staff_id FROM staff_idol WHERE idol_id=$idol_id LIMIT 1");
if (!$q || mysqli_num_rows($q) === 0) {
    die("Idol belum dikelola staff.");
}
$staff_id = mysqli_fetch_assoc($q)['staff_id'];

// Ambil data idol (username + foto)
$idol_result = mysqli_query($conn, "SELECT username, profile_pic FROM users WHERE id=$idol_id AND role='idol'");
if (!$idol_result || mysqli_num_rows($idol_result) === 0) {
    die("Idol tidak ditemukan.");
}
$idol_data = mysqli_fetch_assoc($idol_result);

// Kirim pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    mysqli_query($conn, "INSERT INTO messages (sender_id, receiver_id, to_staff_id, idol_id, message, sender_role, created_at) 
                         VALUES ($penggemar_id, NULL, $staff_id, $idol_id, '$msg', 'penggemar', NOW())");
}

// Ambil riwayat chat
$chat = mysqli_query($conn, "
    SELECT * FROM messages 
    WHERE idol_id=$idol_id AND (
        (sender_id=$penggemar_id AND sender_role='penggemar') OR
        (receiver_id=$penggemar_id AND sender_role='staff')
    )
    ORDER BY created_at ASC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat dengan <?= htmlspecialchars($idol_data['username']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="chat-dark">
<div class="chat-wrapper">
    <div class="chat-container">
        <div class="chat-header">
            <a href="view_profile.php?id=<?= $idol_id ?>" class="chat-back">←</a>
            <img src="../assets/uploads/<?= $idol_data['profile_pic'] ?? 'default.png' ?>" class="chat-idol-pic">
            <span class="chat-idol-name"><?= htmlspecialchars($idol_data['username']) ?></span>
        </div>

        <div class="chat-messages" id="messageArea">
            <?php while ($row = mysqli_fetch_assoc($chat)): ?>
                <div class="chat-bubble <?= $row['sender_role'] === 'penggemar' ? 'right' : 'left' ?>">
                    <?= htmlspecialchars($row['message']) ?>
                    <div class="chat-time"><?= date("H:i", strtotime($row['created_at'])) ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <form method="POST" class="chat-form">
            <input type="text" name="message" placeholder="Ketik pesan..." required autocomplete="off">
            <button type="submit">➤</button>
        </form>
    </div>
</div>
<script src="../assets/js/script.js"></script>
</body>
</html>
