<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'staff') {
    header("Location: ../login.php");
    exit();
}

$backLink = ($_SESSION['user']['role'] === 'admin') ? '../front-end/admin/dashboard.php' : '../front-end/home.php';

$result = mysqli_query($conn, "SELECT * FROM merchandise");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Merchandise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Kelola Merchandise</h2>

<a href="add_merchandise.php" class="btn">Tambah Merchandise</a>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<div class="product-card">
    <h3><?= $row['name'] ?></h3>
    <img src="../assets/uploads/<?= $row['image'] ?>"><br>
    <p>Harga: <?= $row['price'] ?></p>
    <p>Stok: <?= $row['stock'] ?></p>
    <p><?= $row['description'] ?></p>
    <a href="edit_merchandise.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
    <a href="delete_merchandise.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
</div>
<?php endwhile; ?>

<a href="<?= $backLink ?>" class="btn-back">⬅ Kembali</a>

</body>
</html>