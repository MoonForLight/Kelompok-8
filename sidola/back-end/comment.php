<?php
session_start();
include "../database/db.php";

if (isset($_POST['post_id'], $_POST['comment'], $_SESSION['user']['id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user']['id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $result = mysqli_query($conn, "INSERT INTO comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')");
    echo $result ? "success" : "failed";
}
?>