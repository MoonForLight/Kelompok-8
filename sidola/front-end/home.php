<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$uid = $user['id'];
$role = $user['role'];

// Admin tidak boleh akses home.php
if ($role === 'admin') {
    header("Location: admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home - SI-Dola</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-left">
        <strong>SI-Dola</strong>
    </div>
    <div class="nav-right">

        <?php if ($role === 'penggemar'): ?>
            <a href="merchandise.php" class="nav-link">🛍️ Merchandise</a>
            <a href="cart.php" class="nav-link">🛒 Keranjang</a>
            <a href="order_history.php" class="nav-link">📜 Riwayat Pesanan</a>
        <?php endif; ?>

        <?php if ($role === 'staff'): ?>
            <a href="../front-end/staff/select_idol.php" class="nav-link">Kelola Idol</a>
            <a href="../back-end/manage_merchandise.php" class="nav-link">Kelola Merchandise</a>
        <?php endif; ?>

        <div class="dropdown">
            <div class="avatar-icon dynamic" id="avatarIcon" style="--avatar-url: url('../assets/uploads/<?= $user['profile_pic'] ?? 'default.png' ?>');"></div>
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="edit_profile.php">👤 Profil</a>
                <a href="logout.php">🔓 Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <h2>Selamat datang, <?= htmlspecialchars($user['username']) ?>!</h2>

    <?php if ($role === 'idol'): ?>
    <div class="post-box">
        <h3>Post Konten Idol</h3>
        <form method="POST" enctype="multipart/form-data" action="../back-end/post.php">
            <textarea name="content" rows="3" placeholder="Tulis sesuatu..."></textarea><br>
            <input type="file" name="media"><br>
            <button type="submit">Posting</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="post-box">
        <h3>Postingan Idol</h3>

        <?php
        if ($role === 'staff') {
            $idolRes = mysqli_query($conn, "
                SELECT posts.*, users.username, users.profile_pic, users.id AS user_id 
                FROM posts
                JOIN users ON posts.user_id = users.id
                JOIN staff_idol ON staff_idol.idol_id = posts.user_id
                WHERE staff_idol.staff_id = $uid
                ORDER BY posts.created_at DESC
            ");
        } else {
            $idolRes = mysqli_query($conn, "
                SELECT posts.*, users.username, users.profile_pic, users.id AS user_id 
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE users.role = 'idol'
                ORDER BY posts.created_at DESC
            ");
        }

        while ($post = mysqli_fetch_assoc($idolRes)):
            $pid = $post['id'];
            $like_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likes WHERE post_id=$pid"));
        ?>
        <div class="card">
            <div class="post-header">
                <?php
                    $profile_link = ($role === 'staff')
                        ? "staff/manage_idol.php?id=" . $post['user_id']
                        : "view_profile.php?id=" . $post['user_id'];
                ?>
                <a href="<?= $profile_link ?>" class="poster-link">
                    <img src="../assets/uploads/<?= $post['profile_pic'] ?? 'default.png' ?>" class="poster-avatar">
                    <span class="poster-username"><?= htmlspecialchars($post['username']) ?></span>
                </a>
            </div>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

            <?php if (!empty($post['media']) && file_exists("../assets/uploads/" . $post['media'])): ?>
                <?php if ($post['media_type'] === 'image'): ?>
                    <img src="../assets/uploads/<?= $post['media'] ?>" width="100%">
                <?php elseif ($post['media_type'] === 'video'): ?>
                    <video width="100%" controls>
                        <source src="../assets/uploads/<?= $post['media'] ?>">
                    </video>
                <?php endif; ?>
            <?php endif; ?>

            <p id="like-count-<?= $pid ?>">❤️ <?= $like_count ?> likes</p>

            <?php if ($role !== 'staff'): ?>
                <button class="like-button" data-post-id="<?= $pid ?>">❤️ Like</button>

                <div class="comment-container">
                    <form class="comment-form">
                        <input type="text" id="comment-input-<?= $pid ?>" class="comment-input" placeholder="Tambahkan komentar...">
                        <button type="button"
                                onclick="submitComment(<?= $pid ?>, '<?= $user['profile_pic'] ?? 'default.png' ?>', '<?= $user['username'] ?>')"
                                id="submit-button-<?= $pid ?>"
                                class="comment-button"
                                disabled>
                            Kirim
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <a href="../back-end/delete_post.php?id=<?= $pid ?>&idol=<?= $post['user_id'] ?>" class="confirm-delete" data-confirm-text="Yakin hapus postingan?">🗑 Hapus Postingan</a>
            <?php endif; ?>

            <div id="comments-<?= $pid ?>">
                <?php
                $comres = mysqli_query($conn, "
                    SELECT comments.id, comments.comment, users.username, users.profile_pic 
                    FROM comments JOIN users ON comments.user_id = users.id 
                    WHERE post_id=$pid ORDER BY comments.created_at DESC
                ");
                while ($c = mysqli_fetch_assoc($comres)):
                ?>
                <div class="comment-item">
                    <img src="../assets/uploads/<?= $c['profile_pic'] ?? 'default.png' ?>" class="circle-thumb-small">
                    <div class="comment-text">
                        <strong><?= htmlspecialchars($c['username']) ?></strong>
                        <span><?= htmlspecialchars($c['comment']) ?></span>
                        <?php if ($role === 'staff'): ?>
                            <a href="../back-end/delete_comment.php?id=<?= $c['id'] ?>&idol=<?= $post['user_id'] ?>" class="confirm-delete" data-confirm-text="Yakin hapus komentar ini?">🗑</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<script src="../assets/js/script.js"></script>
</body>
</html>