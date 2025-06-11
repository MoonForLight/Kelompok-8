<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'staff') {
    header("Location: ../login.php");
    exit();
}

$backLink = ($_SESSION['user']['role'] === 'admin') ? '../front-end/admin/dashboard.php' : '../front-end/home.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image_name = $_FILES['image_file']['name'];
    $image_tmp = $_FILES['image_file']['tmp_name'];
    move_uploaded_file($image_tmp, "../assets/uploads/" . $image_name);

    mysqli_query($conn, "INSERT INTO merchandise (name, description, price, stock, image) VALUES ('$name','$desc','$price','$stock','$image_name')");
    header("Location: manage_merchandise.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Merchandise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Tambah Merchandise</h2>

<form method="POST" enctype="multipart/form-data" class="product-card">
    Nama: <input type="text" name="name" required><br>
    Deskripsi: <textarea name="description"></textarea><br>
    Harga: <input type="number" step="0.01" name="price" required><br>
    Stok: <input type="number" name="stock" required><br>
    Upload Gambar: <input type="file" name="image_file" required><br>
    <input type="submit" name="submit" value="Tambah" class="btn">
</form>

<a href="<?= $backLink ?>" class="btn-back">⬅ Kembali</a>

</body>
</html>