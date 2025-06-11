<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'penggemar') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$result = mysqli_query($conn, "
    SELECT * FROM merchandise_orders 
    WHERE user_id='$user_id' 
    ORDER BY order_date DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Riwayat Pesanan</h2>

<?php while ($order = mysqli_fetch_assoc($result)): ?>
    <div class="product-card">
        <h3>Order #<?= $order['id'] ?> - Total: <?= $order['total_price'] ?> - Tanggal: <?= $order['order_date'] ?></h3>
        <ul>
        <?php
        $order_id = $order['id'];
        $items = mysqli_query($conn, "
            SELECT merchandise.name, merchandise.image, merchandise_order_items.* 
            FROM merchandise_order_items 
            JOIN merchandise ON merchandise_order_items.merchandise_id = merchandise.id 
            WHERE order_id='$order_id'
        ");
        while ($item = mysqli_fetch_assoc($items)): ?>
            <li>
                <img src="../assets/uploads/<?= $item['image'] ?>" width="50"> 
                <?= $item['name'] ?> - Qty: <?= $item['quantity'] ?> - Harga: <?= $item['price'] ?>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>
<?php endwhile; ?>

<a href="home.php" class="btn-back">⬅ Kembali ke Home</a>

</body>
</html>
