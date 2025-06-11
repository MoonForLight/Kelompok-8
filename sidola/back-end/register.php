<?php
include "../database/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = $_POST["email"];
    $role = isset($_POST["role"]) ? $_POST["role"] : "penggemar";

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $email, $role);

    if ($stmt->execute()) {
        echo "Registrasi berhasil!";
        header("Location: ../index.php");
    } else {
        echo "Gagal: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
