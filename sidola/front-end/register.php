<?php
session_start();
include "../database/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Cek apakah username sudah digunakan
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) == 0) {
        // Insert user baru dengan email
        mysqli_query($conn, "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')");
        $success = "Berhasil daftar! <a href='../front-end/login.php'>Login sekarang</a>";
    } else {
        $error = "Username sudah digunakan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - SI-Dola</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <form method="POST">
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="hidden" name="role" value="penggemar" />
        <button type="submit">Daftar</button>
    </form>
</div>
</body>
</html>
