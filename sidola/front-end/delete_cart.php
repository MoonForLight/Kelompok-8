<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'penggemar') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM cart WHERE id='$id'");
header("Location: cart.php");
?>
