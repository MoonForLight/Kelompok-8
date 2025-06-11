<?php
session_start();
include("../../database/db.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Ambil statistik
$response = [
    "users" => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'],
    "idol" => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='idol'"))['total'],
    "staff" => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='staff'"))['total'],
    "penggemar" => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='penggemar'"))['total'],
    "posts" => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM posts"))['total'],
    "comments" => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM comments"))['total'],
    "likes" => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM likes"))['total'],
];

echo json_encode($response);
