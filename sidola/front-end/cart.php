<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'penggemar') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$result = mysqli_query($conn, "
    SELECT cart.id as cart_id, merchandise.*, cart.quantity 
    FROM cart 
    JOIN merchandise ON cart.merchandise_id = merchandise.id 
    WHERE cart.user_id='$user_id'
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Keranjang Belanja</h2>

<?php while ($row = mysqli_fetch_assoc($result)): 
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
?>
<div class="product-card">
    <h3><?= $row['name'] ?></h3>
    <img src="../assets/uploads/<?= $row['image'] ?>"><br>
    <p>Harga: <?= $row['price'] ?></p>
    <p>Jumlah: <?= $row['quantity'] ?></p>
    <p>Subtotal: <?= $subtotal ?></p>
    <a href="delete_cart.php?id=<?= $row['cart_id'] ?>" class="btn" onclick="return confirm('Yakin hapus?')">Hapus</a>
</div>
<?php endwhile; ?>

<h3>Total: <?= $total ?></h3>

<?php if ($total > 0): ?>
    <form method="POST" action="checkout.php">
        <input type="submit" name="checkout" value="Checkout" class="btn">
    </form>
<?php endif; ?>

<a href="merchandise.php" class="btn-back">⬅ Kembali ke Daftar Merchandise</a>

</body>
</html>
