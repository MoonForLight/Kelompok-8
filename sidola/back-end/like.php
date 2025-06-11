<?php
session_start();
include "../database/db.php";

if (isset($_POST['post_id'], $_SESSION['user']['id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user']['id'];

    // Cek apakah user sudah like
    $check = mysqli_query($conn, "SELECT * FROM likes WHERE post_id=$post_id AND user_id=$user_id");
    if (mysqli_num_rows($check) > 0) {
        // Jika sudah like → hapus (unlike)
        mysqli_query($conn, "DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id");
    } else {
        // Jika belum like → tambahkan
        mysqli_query($conn, "INSERT INTO likes (post_id, user_id) VALUES ($post_id, $user_id)");
    }

    // Ambil jumlah like terbaru
    $like_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM likes WHERE post_id=$post_id");
    $total = mysqli_fetch_assoc($like_result)['total'] ?? 0;

    echo $total;
}
?>