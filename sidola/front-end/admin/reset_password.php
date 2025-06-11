<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$success = "";
$error = "";

// Proses jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update = mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id=$user_id");

    if ($update) {
        $success = "Password berhasil direset.";
    } else {
        $error = "Gagal mereset password.";
    }
}

// Ambil semua pengguna
$result = mysqli_query($conn, "SELECT id, username FROM users ORDER BY username ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password Pengguna</h2>

        <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <label for="user_id">Pilih Pengguna:</label>
            <select name="user_id" required>
                <?php while ($user = mysqli_fetch_assoc($result)) : ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label for="new_password">Password Baru:</label><br>
            <input type="password" name="new_password" required><br><br>

            <button type="submit">Reset Password</button>
        </form>
        <br>
        <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
    </div>
</body>
</html>