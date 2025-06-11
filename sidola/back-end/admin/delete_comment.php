<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../front-end/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);

    mysqli_query($conn, "DELETE FROM comments WHERE id = $comment_id");

    header("Location: ../../front-end/admin/delete_content.php");
    exit;
} else {
    echo "ID komentar tidak ditemukan.";
}
