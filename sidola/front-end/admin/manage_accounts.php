<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua akun
$query = "SELECT * FROM users ORDER BY role";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

// Tambah akun jika form dikirim
$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = hash('sha256', $_POST['password']);
    $role = $_POST['role'];

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Username atau email sudah digunakan.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')");
        $pesan = $insert ? "Akun berhasil ditambahkan." : "Gagal menambahkan akun.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Akun</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        img.avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
            vertical-align: middle;
        }
        .username-cell {
            display: flex;
            align-items: center;
        }
        #formTambah, #formInput {
            display: none;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kelola Akun Pengguna</h2>

        <?php if ($pesan): ?>
            <p style="color: <?= strpos($pesan, 'berhasil') !== false ? 'green' : 'red' ?>;"><?= $pesan ?></p>
        <?php endif; ?>

        <button onclick="toggleTambah()">+ Tambah Akun</button>

        <!-- Pilih Role -->
        <div id="formTambah">
            <br>
            <label for="selectRole">Pilih Jenis Akun:</label>
            <select id="selectRole" onchange="showInputForm()">
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="idol">Idol</option>
            </select>
        </div>

        <!-- Form Input Akun -->
        <div id="formInput">
            <form method="POST">
                <input type="hidden" name="role" id="roleHidden">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Simpan Akun</button>
            </form>
        </div>

        <br><br>
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td class="username-cell">
                        <img src="../../assets/uploads/<?= $user['profile_pic'] ?? 'default.png' ?>" class="avatar">
                        <?= htmlspecialchars($user['username']) ?>
                    </td>
                    <td><?= htmlspecialchars($user['email'] ?? '-') ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>Aktif</td>
                    <td>
                        <?php if ($user['role'] !== 'admin'): ?>
                            <a href="../../back-end/admin/delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Yakin ingin menghapus akun ini?')">🗑 Hapus</a>
                        <?php else: ?>
                            <span style="color: gray;">(admin)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
    </div>
    <script src="../../assets/js/script.js"></script>
</body>
</html>