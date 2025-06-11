<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'penggemar') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

if (isset($_POST['submit'])) {
    $merchandise_id = $_POST['merchandise_id'];
    $quantity = $_POST['quantity'];

    // Cek apakah sudah ada item yg sama di cart
    $cek = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND merchandise_id='$merchandise_id'");
    if (mysqli_num_rows($cek) > 0) {
        // Update jumlah
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + $quantity WHERE user_id='$user_id' AND merchandise_id='$merchandise_id'");
    } else {
        // Insert baru
        mysqli_query($conn, "INSERT INTO cart (user_id, merchandise_id, quantity) VALUES ('$user_id', '$merchandise_id', '$quantity')");
    }

    header("Location: cart.php");
}
?>
