<?php
session_start();
include "../database/db.php";

if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'staff') {
    header("Location: ../login.php");
    exit();
}

$backLink = ($_SESSION['user']['role'] === 'admin') ? '../front-end/admin/dashboard.php' : '../front-end/home.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM merchandise WHERE id='$id'");
$data = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    if (!empty($_FILES['image_file']['name'])) {
        $image_name = $_FILES['image_file']['name'];
        $image_tmp = $_FILES['image_file']['tmp_name'];
        move_uploaded_file($image_tmp, "../assets/uploads/" . $image_name);

        mysqli_query($conn, "UPDATE merchandise SET name='$name', description='$desc', price='$price', stock='$stock', image='$image_name' WHERE id='$id'");
    } else {
        mysqli_query($conn, "UPDATE merchandise SET name='$name', description='$desc', price='$price', stock='$stock' WHERE id='$id'");
    }

    header("Location: manage_merchandise.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Merchandise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Edit Merchandise</h2>

<form method="POST" enctype="multipart/form-data" class="product-card">
    Nama: <input type="text" name="name" value="<?= $data['name'] ?>" required><br>
    Deskripsi: <textarea name="description"><?= $data['description'] ?></textarea><br>
    Harga: <input type="number" step="0.01" name="price" value="<?= $data['price'] ?>" required><br>
    Stok: <input type="number" name="stock" value="<?= $data['stock'] ?>" required><br>
    Upload Gambar Baru (Opsional): <input type="file" name="image_file"><br>
    <br>Gambar Saat Ini:<br>
    <img src="../assets/uploads/<?= $data['image'] ?>" width="100"><br><br>
    <input type="submit" name="submit" value="Update" class="btn">
</form>

<a href="<?= $backLink ?>" class="btn-back">⬅ Kembali</a>

</body>
</html>