<?php
session_start();
include "../database/db.php";

$user = $_SESSION['user'];
$content = $_POST['content'] ?? '';
$uid = $user['id'];

$media = $_FILES['media'];
$filename = "";
$type = "none";

// Cek apakah ada media diupload
if ($media['name']) {
    $filename = time() . "_" . basename($media['name']);
    $target = "../assets/uploads/" . $filename;

    // Tentukan ekstensi
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed_image = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_video = ['mp4', 'webm'];

    if (in_array($ext, array_merge($allowed_image, $allowed_video))) {
        if (move_uploaded_file($media["tmp_name"], $target)) {
            // Tentukan jenis media
            if (in_array($ext, $allowed_image)) {
                $type = 'image';
            } elseif (in_array($ext, $allowed_video)) {
                $type = 'video';
            }
        } else {
            die("Upload file gagal. Periksa folder uploads dan izin file.");
        }
    } else {
        die("Jenis file tidak diizinkan.");
    }
}

// Validasi: jangan izinkan post kosong semua
if (empty($content) && $type === 'none') {
    $_SESSION['error'] = "Tidak boleh posting kosong tanpa teks dan tanpa media.";
    header("Location: ../front-end/home.php");
    exit();
}

// Simpan ke database
mysqli_query($conn, "INSERT INTO posts (user_id, content, media, media_type) 
    VALUES ('$uid', '$content', '$filename', '$type')");

header("Location: ../front-end/home.php");
exit();
