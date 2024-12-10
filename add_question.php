<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $level = $_POST['level'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $sql = "INSERT INTO quiz_questions (level, question, answer) VALUES ('$level', '$question', '$answer')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
