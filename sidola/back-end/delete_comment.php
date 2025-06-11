<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'staff') exit("Akses ditolak.");

$comment_id = intval($_GET['id']);
$idol_id = intval($_GET['idol']);

mysqli_query($conn, "DELETE FROM comments WHERE id=$comment_id");

header("Location: ../front-end/staff/manage_idol.php?id=$idol_id");
exit;