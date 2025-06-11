<?php
session_start();
include("../../database/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../front-end/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update = mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id=$user_id");

    if ($update) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
