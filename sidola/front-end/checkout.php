<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'penggemar') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$result = mysqli_query($conn, "
    SELECT cart.*, merchandise.price, merchandise.stock 
    FROM cart 
    JOIN merchandise ON cart.merchandise_id = merchandise.id 
    WHERE cart.user_id='$user_id'
");

$total_price = 0;
$order_items = [];

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['quantity'] > $row['stock']) {
        echo "Stok tidak cukup untuk " . $row['merchandise_id'];
        exit();
    }
    $subtotal = $row['price'] * $row['quantity'];
    $total_price += $subtotal;
    $order_items[] = $row;
}

if (count($order_items) > 0) {
    // Insert order
    mysqli_query($conn, "INSERT INTO merchandise_orders (user_id, total_price) VALUES ('$user_id','$total_price')");
    $order_id = mysqli_insert_id($conn);

    // Insert order items & update stock
    foreach ($order_items as $item) {
        mysqli_query($conn, "INSERT INTO merchandise_order_items (order_id, merchandise_id, quantity, price) VALUES ('$order_id', '{$item['merchandise_id']}', '{$item['quantity']}', '{$item['price']}')");
        mysqli_query($conn, "UPDATE merchandise SET stock = stock - {$item['quantity']} WHERE id='{$item['merchandise_id']}'");
    }

    // Clear cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

    header("Location: order_history.php");
} else {
    echo "Keranjang kosong.";
}
?>
