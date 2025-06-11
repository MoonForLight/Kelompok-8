<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'penggemar') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM merchandise WHERE id='$id'");
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Merchandise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Detail Merchandise</h2>

<div class="product-card">
    <h3><?= $data['name'] ?></h3>
    <img src="../assets/uploads/<?= $data['image'] ?>"><br>
    <p>Harga: <?= $data['price'] ?></p>
    <p>Stok: <?= $data['stock'] ?></p>
    <p><?= $data['description'] ?></p>

    <form method="POST" action="add_to_cart.php">
        <input type="hidden" name="merchandise_id" value="<?= $data['id'] ?>">
        Jumlah: <input type="number" name="quantity" min="1" max="<?= $data['stock'] ?>" value="1"><br>
        <input type="submit" name="submit" value="Tambah ke Keranjang" class="btn">
    </form>
</div>

<a href="merchandise.php" class="btn-back">⬅ Kembali ke Daftar Merchandise</a>

</body>
</html>
