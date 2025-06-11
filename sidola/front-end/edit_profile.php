<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../front-end/login.php");
    exit();
}

$user = $_SESSION['user'];
$id = $user['id'];
$role = $user['role'];

if (isset($_GET['delete_pic']) && !empty($user['profile_pic'])) {
    $filepath = "../assets/uploads/" . $user['profile_pic'];
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    mysqli_query($conn, "UPDATE users SET profile_pic=NULL WHERE id=$id");
    $_SESSION['user'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$id"));
    header("Location: ../front-end/edit_profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $profile_pic = $user['profile_pic'] ?? '';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $filename = time() . "_" . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], "../assets/uploads/" . $filename);
        $profile_pic = $filename;
        mysqli_query($conn, "UPDATE users SET username='$username', email='$email', profile_pic='$profile_pic' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE users SET username='$username', email='$email' WHERE id=$id");
    }

    $_SESSION['user'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$id"));
    header("Location: ../front-end/home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Profil</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <?php if ($role !== 'admin'): ?>
            <label for="profile_pic">Foto Profil</label>
            <input type="file" name="profile_pic" id="profile_pic">

            <?php if (!empty($user['profile_pic'])): ?>
                <div class="profile-preview">
                    <img src="../assets/uploads/<?= $user['profile_pic'] ?>" alt="Foto Profil">
                    <a class="delete-link" href="../front-end/edit_profile.php?delete_pic=1">🗑 Hapus Foto Profil</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <button type="submit">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
