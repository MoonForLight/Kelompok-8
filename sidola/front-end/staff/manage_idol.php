<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header("Location: ../front-end/login.php");
    exit;
}

$staff_id = $_SESSION['user']['id'];
$idol_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validasi relasi staff-idol
$cek = mysqli_query($conn, "SELECT * FROM staff_idol WHERE staff_id=$staff_id AND idol_id=$idol_id");
if (mysqli_num_rows($cek) === 0) {
    die("Akses ditolak. Idol tidak terverifikasi.");
}

// Ambil data idol
$idol = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$idol_id"));

// Ambil semua postingan idol
$postingan = mysqli_query($conn, "SELECT * FROM posts WHERE user_id=$idol_id ORDER BY created_at DESC");

// Statistik
$total_like = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM likes l 
    JOIN posts p ON l.post_id = p.id 
    WHERE p.user_id=$idol_id
"))['total'];

$total_follower = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM follows 
    WHERE idol_id=$idol_id
"))['total'];

$jumlah_postingan = mysqli_num_rows($postingan);

// Hitung jumlah penggemar unik yang mengirim pesan
$jumlah_pesan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(DISTINCT sender_id) as total 
    FROM messages 
    WHERE idol_id=$idol_id AND to_staff_id=$staff_id AND sender_role='penggemar'
"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Idol - <?= htmlspecialchars($idol['username']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="profile-container">
    <div class="profile-header">
        <a href="../home.php" class="close-btn">×</a>
        <img src="../../assets/uploads/<?= $idol['profile_pic'] ?>" alt="Profile" class="avatar-large">
        <div class="profile-info">
            <h2><?= htmlspecialchars($idol['username']) ?></h2>
            <p><?= $jumlah_postingan ?> Postingan • <?= $total_follower ?> Followers • <?= $total_like ?> Likes</p>
        </div>
    </div>

    <div class="inbox-box" style="margin-top: 10px; margin-bottom: 20px;">
        <a href="inbox_staff.php?id=<?= $idol_id ?>" class="btn-chat">📥 Lihat Chat</a>
    </div>

    <h3>Daftar Postingan</h3>
    <?php while ($post = mysqli_fetch_assoc($postingan)): ?>
        <div class="card">
            <div class="post-header">
                <img src="../../assets/uploads/<?= $idol['profile_pic'] ?>" class="poster-avatar">
                <strong><?= htmlspecialchars($idol['username']) ?></strong>
            </div>

            <?php if ($post['content']) echo "<p>" . htmlspecialchars($post['content']) . "</p>"; ?>

            <?php if ($post['media']) :
                $ext = pathinfo($post['media'], PATHINFO_EXTENSION);
                if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                    <img src="../../assets/uploads/<?= $post['media'] ?>" class="media-image">
                <?php elseif (in_array($ext, ['mp4','webm'])): ?>
                    <video controls class="media-video">
                        <source src="../../assets/uploads/<?= $post['media'] ?>" type="video/<?= $ext ?>">
                    </video>
                <?php endif; ?>
            <?php endif; ?>

            <a href="../../back-end/delete_post.php?id=<?= $post['id'] ?>&idol=<?= $idol_id ?>" class="delete-link" onclick="return confirm('Yakin hapus postingan ini?')">🗑 Hapus Postingan</a>

            <div class="comment-list">
                <?php
                $comments = mysqli_query($conn, "
                    SELECT c.*, u.username, u.profile_pic 
                    FROM comments c 
                    JOIN users u ON c.user_id = u.id 
                    WHERE c.post_id=" . $post['id']
                );  
                while ($comment = mysqli_fetch_assoc($comments)):
                ?>
                <div class="comment-item">
                    <img src="../../assets/uploads/<?= $comment['profile_pic'] ?? 'default.png' ?>">
                    <div class="comment-text">
                        <strong><?= htmlspecialchars($comment['username']) ?>:</strong> <?= htmlspecialchars($comment['comment']) ?>
                        <br>
                        <a href="../../back-end/delete_comment.php?id=<?= $comment['id'] ?>&idol=<?= $idol_id ?>" onclick="return confirm('Hapus komentar ini?')">🗑</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
