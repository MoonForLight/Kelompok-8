<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data statistik
$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$idolCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='idol'"))['total'];
$staffCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='staff'"))['total'];
$postCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM posts"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard Admin</h2>
        <div class="card">
            <h3>Total Pengguna: <?= $userCount ?></h3>
        </div>
        <div class="card">
            <h3>Total Idol: <?= $idolCount ?></h3>
        </div>
        <div class="card">
            <h3>Total Staff: <?= $staffCount ?></h3>
        </div>
        <div class="card">
            <h3>Total Postingan: <?= $postCount ?></h3>
        </div>

        <div class="menu">
            <a href="manage_accounts.php">Kelola Akun</a> |
            <a href="reset_password.php">Reset Password</a> |
            <a href="delete_content.php">Hapus Konten</a> |
            <a href="reports.php">Laporan</a> |
            <a href="../../back-end/manage_merchandise.php">Kelola Merchandise</a> |
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>
</html>