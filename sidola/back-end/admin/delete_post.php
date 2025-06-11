<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../front-end/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);

    mysqli_query($conn, "DELETE FROM likes WHERE post_id = $post_id");

    mysqli_query($conn, "DELETE FROM comments WHERE post_id = $post_id");

    mysqli_query($conn, "DELETE FROM posts WHERE id = $post_id");

    header("Location: ../../front-end/admin/delete_content.php");
    exit;
} else {
    echo "ID postingan tidak ditemukan.";
}
