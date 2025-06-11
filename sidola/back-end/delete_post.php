<?php
session_start();
include "../database/db.php";

if (in_array($_SESSION['user']['role'], ['penggemar'])) {
    exit("Akses ditolak.");
}

$post_id = intval($_GET['id']);
$idol_id = intval($_GET['idol']);

mysqli_query($conn, "DELETE FROM comments WHERE post_id=$post_id");
mysqli_query($conn, "DELETE FROM likes WHERE post_id=$post_id");
mysqli_query($conn, "DELETE FROM posts WHERE id=$post_id");

header("Location: ../front-end/staff/manage_idol.php?id=$idol_id");
exit;