<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Statistik jumlah pengguna
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_idols = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='idol'"))['total'];
$total_staffs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='staff'"))['total'];
$total_penggemar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='penggemar'"))['total'];

// Statistik aktivitas
$total_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM posts"))['total'];
$total_comments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM comments"))['total'];
$total_likes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM likes"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aktivitas</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Laporan & Statistik Pengguna</h2>

        <h3>Jumlah Pengguna</h3>
        <ul>
            <li>Total: <?= $total_users ?></li>
            <li>Idol: <?= $total_idols ?></li>
            <li>Staff: <?= $total_staffs ?></li>
            <li>Penggemar: <?= $total_penggemar ?></li>
        </ul>

        <h3>Aktivitas Pengguna</h3>
        <ul>
            <li>Total Postingan: <?= $total_posts ?></li>
            <li>Total Komentar: <?= $total_comments ?></li>
            <li>Total Likes: <?= $total_likes ?></li>
        </ul>

        <br>
        <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
    </div>
</body>
</html>