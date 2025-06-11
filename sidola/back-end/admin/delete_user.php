<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../front-end/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
    $user = mysqli_fetch_assoc($cek);

    if ($user && $user['role'] !== 'admin') {
        // 1. Hapus komentar yang ditulis user
        mysqli_query($conn, "DELETE FROM comments WHERE user_id = $user_id");

        // 2. Hapus komentar di postingan user
        $post_q = mysqli_query($conn, "SELECT id FROM posts WHERE user_id = $user_id");
        while ($p = mysqli_fetch_assoc($post_q)) {
            $pid = $p['id'];
            mysqli_query($conn, "DELETE FROM comments WHERE post_id = $pid");
            mysqli_query($conn, "DELETE FROM likes WHERE post_id = $pid");
        }

        // 3. Hapus likes dari user
        mysqli_query($conn, "DELETE FROM likes WHERE user_id = $user_id");

        // 4. Hapus posts
        mysqli_query($conn, "DELETE FROM posts WHERE user_id = $user_id");

        // 5. Hapus follows
        mysqli_query($conn, "DELETE FROM follows WHERE penggemar_id = $user_id OR idol_id = $user_id");

        // 6. Hapus messages (pengirim/penerima)
        mysqli_query($conn, "DELETE FROM messages WHERE sender_id = $user_id OR receiver_id = $user_id");

        // 7. Hapus relasi staff_idol
        mysqli_query($conn, "DELETE FROM staff_idol WHERE staff_id = $user_id OR idol_id = $user_id");

        // 8. Hapus akun user
        mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");

        header("Location: ../../front-end/admin/manage_accounts.php");
        exit;
    } else {
        echo "Akun admin tidak bisa dihapus.";
    }
} else {
    echo "ID tidak ditemukan.";
}
