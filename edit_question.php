<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $level = $_POST['level'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Update the question in the database
    $sql = "UPDATE quiz_questions SET level='$level', question='$question', answer='$answer' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php?success=1');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
