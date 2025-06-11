<?php
session_start();
include "../database/db.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data idol
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id AND role='idol'");
if (!$result) die("Query error: " . mysqli_error($conn));
$idol = mysqli_fetch_assoc($result);
if (!$idol) die("Idol tidak ditemukan.");

// Ambil semua postingan idol
$postingan_result = mysqli_query($conn, "SELECT * FROM posts WHERE user_id=$id ORDER BY created_at DESC");
$jumlah_postingan = mysqli_num_rows($postingan_result);

// Total Likes
$total_like = 0;
$likes_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM likes l JOIN posts p ON l.post_id = p.id WHERE p.user_id=$id");
if ($likes_result && $row = mysqli_fetch_assoc($likes_result)) {
    $total_like = $row['total'];
}

// Followers
$jumlah_followers = 0;
$followers_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM follows WHERE idol_id=$id");
if ($followers_result && $row = mysqli_fetch_assoc($followers_result)) {
    $jumlah_followers = $row['total'];
}

// Follow/Unfollow & Chat
$is_following = false;
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'penggemar') {
    $current_user_id = $_SESSION['user']['id'];

    $follow_check = mysqli_query($conn, "SELECT * FROM follows WHERE fan_id=$current_user_id AND idol_id=$id");
    if ($follow_check) {
        $is_following = mysqli_num_rows($follow_check) > 0;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['follow'])) {
            mysqli_query($conn, "INSERT INTO follows (fan_id, idol_id) VALUES ($current_user_id, $id)");
        } elseif (isset($_POST['unfollow'])) {
            mysqli_query($conn, "DELETE FROM follows WHERE fan_id=$current_user_id AND idol_id=$id");
        }
        header("Location: view_profile.php?id=$id");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Idol</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="profile-container">
    <div class="profile-header relative">
        <a href="home.php" class="back-home" title="Kembali ke Home">×</a>
        <img src="../assets/uploads/<?= htmlspecialchars($idol['profile_pic'] ?? 'default.png') ?>" alt="Foto Profil">
        <h2><?= htmlspecialchars($idol['username']) ?></h2>
    </div>

    <div class="stat-box">
        <div><strong><?= $jumlah_postingan ?></strong><br>Postingan</div>
        <div><strong><?= $jumlah_followers ?></strong><br>Followers</div>
        <div><strong><?= $total_like ?></strong><br>Likes</div>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'penggemar'): ?>
            <form method="POST" style="margin-top: 10px;">
                <?php if ($is_following): ?>
                    <button type="submit" name="unfollow" class="btn-unfollow">Unfollow</button>
                <?php else: ?>
                    <button type="submit" name="follow" class="btn-follow">Follow</button>
                <?php endif; ?>
            </form>

            <!-- Tombol Chat -->
            <form method="GET" action="chat.php" style="margin-top: 10px;">
                <input type="hidden" name="id" value="<?= $idol['id'] ?>">
                <button type="submit" class="btn-chat">💬 Chat</button>
            </form>
        <?php endif; ?>
    </div>

    <h3 class="profile-posting-title">Postingan</h3>
    <?php while ($row = mysqli_fetch_assoc($postingan_result)) :
        $post_id = $row['id'];
        $like_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likes WHERE post_id=$post_id"));
    ?>
        <div class="card">
            <div class="post-header post-header-inline">
                <img src="../assets/uploads/<?= $idol['profile_pic'] ?>" class="avatar-small">
                <strong><?= htmlspecialchars($idol['username']) ?></strong>
                <span style="margin-left:auto; font-size:12px; color:#666;"><?= $row['created_at'] ?></span>
            </div>

            <?php if ($row['content']) echo "<p>" . htmlspecialchars($row['content']) . "</p>"; ?>

            <?php if ($row['media']) :
                $ext = pathinfo($row['media'], PATHINFO_EXTENSION);
                if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                    <img src="../assets/uploads/<?= $row['media'] ?>" class="media-image">
                <?php elseif (in_array($ext, ['mp4','webm'])): ?>
                    <video controls class="media-video">
                        <source src="../assets/uploads/<?= $row['media'] ?>" type="video/<?= $ext ?>">
                    </video>
                <?php endif; ?>
            <?php endif; ?>

            <p id="like-count-<?= $post_id ?>">❤️ <?= $like_count ?> likes</p>
            <button onclick="likePost(<?= $post_id ?>)">❤️ Like</button>

            <div class="comment-container">
                <form class="comment-form">
                    <input type="text" id="comment-input-<?= $post_id ?>" class="comment-input" placeholder="Tambahkan komentar...">
                    <button type="button"
                            onclick="submitComment(<?= $post_id ?>, '<?= $_SESSION['user']['profile_pic'] ?? 'default.png' ?>', '<?= $_SESSION['user']['username'] ?>')"
                            id="submit-button-<?= $post_id ?>"
                            class="comment-button"
                            disabled>
                        Kirim
                    </button>
                </form>
            </div>

            <div id="comments-<?= $post_id ?>">
                <?php
                $comment_query = mysqli_query($conn, "
                    SELECT c.comment, u.username, u.profile_pic 
                    FROM comments c
                    JOIN users u ON c.user_id = u.id
                    WHERE c.post_id = $post_id
                ");
                while ($comment = mysqli_fetch_assoc($comment_query)) {
                    echo "<div class='comment-item'>";
                    echo "<img src='../assets/uploads/{$comment['profile_pic']}' class='circle-thumb-small'>";
                    echo "<div class='comment-text'><strong>" . htmlspecialchars($comment['username']) . "</strong><span>" . htmlspecialchars($comment['comment']) . "</span></div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<script src="../assets/js/script.js"></script>
</body>
</html>
