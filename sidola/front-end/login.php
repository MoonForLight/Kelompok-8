<?php
session_start();
include "../database/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user'] = mysqli_fetch_assoc($result);

        if ($_SESSION['user']['role'] === 'admin') {
            header("Location: ../front-end/admin/dashboard.php");
        } else {
            header("Location: ../front-end/home.php");
        }
        exit();
    } else {
        $error = "Login gagal!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SI-Dola</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <form method="POST">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <input name="username" placeholder="Username" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <div class="notice">Belum punya akun? <a href="register.php">Daftar</a></div>
    </form>
</div>
<script src="../assets/js/script.js"></script>
</body>
</html>
