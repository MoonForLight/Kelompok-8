<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'staff') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM merchandise WHERE id='$id'");
header("Location: manage_merchandise.php");
?>