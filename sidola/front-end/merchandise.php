<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'penggemar') {
    header("Location: index.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM merchandise");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Merchandise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Daftar Merchandise</h2>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<div class="product-card">
    <h3><?= $row['name'] ?></h3>
    <img src="../assets/uploads/<?= $row['image'] ?>"><br>
    <p>Harga: <?= $row['price'] ?></p>
    <p>Stok: <?= $row['stock'] ?></p>
    <a href="merchandise_detail.php?id=<?= $row['id'] ?>" class="btn">Lihat Detail</a>
</div>
<?php endwhile; ?>

<a href="home.php" class="btn-back">⬅ Kembali ke Home</a>

</body>
</html>
