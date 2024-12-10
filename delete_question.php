<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM quiz_questions WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php?deleted=1');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
