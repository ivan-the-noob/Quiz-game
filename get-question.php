<?php
include 'db.php'; 
if (isset($_GET['level'])) {
    $level = $_GET['level'];

    $sql = "SELECT * FROM quiz_questions WHERE level = ? ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $level);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $question = $row['question'];
        $answer = $row['answer'];

        echo $question . "," . $answer;
    } else {
        echo "No question found for level: " . $level;
    }

    $stmt->close();
} else {
    echo "Level parameter missing.";
}

$conn->close();
?>
