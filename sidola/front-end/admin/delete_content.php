<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua postingan
$post_query = mysqli_query($conn, "
    SELECT p.*, u.username, u.profile_pic 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");

// Ambil semua komentar beserta postingannya
$comment_query = mysqli_query($conn, "
    SELECT c.id as comment_id, c.comment, c.created_at as comment_time,
           u.username, u.profile_pic,
           p.content as post_content, p.media, p.media_type
    FROM comments c 
    JOIN users u ON c.user_id = u.id
    JOIN posts p ON c.post_id = p.id
    ORDER BY c.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Konten</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Hapus Postingan</h2>
        <?php while ($post = mysqli_fetch_assoc($post_query)) : ?>
            <div class="card">
                <div style="display: flex; align-items: center;">
                    <img src="../../assets/uploads/<?= $post['profile_pic'] ?? 'default.png' ?>" class="avatar">
                </div>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <?php if (!empty($post['media']) && file_exists("../../assets/uploads/" . $post['media'])): ?>
                    <?php if ($post['media_type'] === 'image'): ?>
                        <img src="../../assets/uploads/<?= $post['media'] ?>" class="media">
                    <?php elseif ($post['media_type'] === 'video'): ?>
                        <video controls class="media">
                            <source src="../../assets/uploads/<?= $post['media'] ?>">
                        </video>
                    <?php endif; ?>
                <?php endif; ?>
                <p>
                    <a href="../../back-end/admin/delete_post.php?id=<?= $post['id'] ?>" class="delete-link">🗑️ Hapus Postingan</a>
                </p>
            </div>
        <?php endwhile; ?>

        <h2>Hapus Komentar</h2>
        <?php while ($comment = mysqli_fetch_assoc($comment_query)) : ?>
            <div class="card">
                <div class="comment-item">
                    <img src="../../assets/uploads/<?= $comment['profile_pic'] ?? 'default.png' ?>" class="circle-thumb-small">
                    <div class="comment-text">
                        <strong><?= htmlspecialchars($comment['username']) ?></strong>
                        <span>Komentar: <?= nl2br(htmlspecialchars($comment['comment'])) ?></span><br>
                        <span style="font-size: 12px; color: gray;">Dikomentari pada: <?= $comment['comment_time'] ?></span>
                    </div>
                </div>
                <p>
                    <a href="../../back-end/delete_comment.php?id=<?= $comment['comment_id'] ?>" class="delete-link">🗑️ Hapus Komentar</a>
                </p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>