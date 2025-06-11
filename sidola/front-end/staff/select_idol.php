<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header("Location: ../../front-end/login.php");
    exit;
}

$staff_id = $_SESSION['user']['id'];
$pesan = "";

// Login Idol untuk dikelola
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_idol'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='idol'");
    if ($q && mysqli_num_rows($q) === 1) {
        $idol = mysqli_fetch_assoc($q);
        $idol_id = $idol['id'];

        mysqli_query($conn, "INSERT IGNORE INTO staff_idol (staff_id, idol_id) VALUES ($staff_id, $idol_id)");
        header("Location: manage_idol.php?id=$idol_id");
        exit;
    } else {
        $pesan = "Username atau password salah, atau bukan akun idol.";
    }
}

// Tambah Idol Baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_idol'])) {
    $username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $email = mysqli_real_escape_string($conn, $_POST['new_email']);
    $password = hash('sha256', $_POST['new_password']);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Username atau email sudah digunakan.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'idol')");
        if ($insert) {
            $idol_id = mysqli_insert_id($conn);
            mysqli_query($conn, "INSERT INTO staff_idol (staff_id, idol_id) VALUES ($staff_id, $idol_id)");
            header("Location: manage_idol.php?id=$idol_id");
            exit;
        } else {
            $pesan = "Gagal menambahkan akun idol.";
        }
    }
}

// Ambil daftar idol yang dikelola staff
$kelola = mysqli_query($conn, "SELECT users.* FROM staff_idol JOIN users ON staff_idol.idol_id = users.id WHERE staff_idol.staff_id = $staff_id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Idol</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Pilih Idol yang Ingin Dikelola</h2>

    <?php if (!empty($pesan)) echo "<p style='color:red;'>$pesan</p>"; ?>

    <form method="POST">
        <input type="hidden" name="login_idol" value="1">
        <input type="text" name="username" placeholder="Username Idol" required><br>
        <input type="password" name="password" placeholder="Password Idol" required><br>
        <button type="submit">Kelola</button>
    </form>

    <hr>
    <button onclick="toggleForm()">Tambah Akun Idol</button>

    <div id="formIdolBaru" style="display:none; margin-top:10px;">
        <form method="POST">
            <input type="hidden" name="register_idol" value="1">
            <input type="text" name="new_username" placeholder="Username Baru" required><br>
            <input type="email" name="new_email" placeholder="Email Idol" required><br>
            <input type="password" name="new_password" placeholder="Password Baru" required><br>
            <button type="submit">Simpan Akun Idol</button>
        </form>
    </div>

    <hr>
    <h3>Idol yang Sudah Anda Kelola:</h3>
    <?php while ($idol = mysqli_fetch_assoc($kelola)): ?>
        <div style="margin-bottom:10px;">
            <a href="manage_idol.php?id=<?= $idol['id'] ?>">
                <img src="../../assets/uploads/<?= $idol['profile_pic'] ?? 'default.png' ?>" width="40" height="40" style="border-radius:50%; vertical-align:middle;">
                <strong><?= htmlspecialchars($idol['username']) ?></strong>
            </a>
        </div>
    <?php endwhile; ?>
</div>

<script src="../../assets/js/script.js"></script>
</body>
</html>
