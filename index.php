<?php
include 'db.php';

$sql = "SELECT * FROM quiz_questions";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Questions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Questions List</h1>
        <!-- Add Question Button -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            Add Question
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Level</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['level']; ?></td>
                    <td><?php echo $row['question']; ?></td>
                    <td><?php echo $row['answer']; ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editQuestionModal<?php echo $row['id']; ?>">
                            Edit
                        </button>
                        <!-- Delete Button -->
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteQuestionModal<?php echo $row['id']; ?>">
                            Delete
                        </button>
                    </td>
                </tr>

                <!-- Edit Question Modal -->
                <div class="modal fade" id="editQuestionModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="edit_question.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Level</label>
                                        <select class="form-select" name="level" required>
                                            <option value="easy" <?php echo ($row['level'] == 'easy' ? 'selected' : ''); ?>>Easy</option>
                                            <option value="medium" <?php echo ($row['level'] == 'medium' ? 'selected' : ''); ?>>Medium</option>
                                            <option value="hard" <?php echo ($row['level'] == 'hard' ? 'selected' : ''); ?>>Hard</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="question" class="form-label">Question</label>
                                        <input type="text" class="form-control" name="question" value="<?php echo $row['question']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="answer" class="form-label">Answer</label>
                                        <input type="text" class="form-control" name="answer" value="<?php echo $row['answer']; ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Question Modal -->
                <div class="modal fade" id="deleteQuestionModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteQuestionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="delete_question.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteQuestionModalLabel">Delete Question</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the question with ID <?php echo $row['id']; ?>?</p>
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Question Modal -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add_question.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addQuestionModalLabel">Add New Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select" name="level" required>
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <input type="text" class="form-control" name="question" required>
                        </div>
                        <div class="mb-3">
                            <label for="answer" class="form-label">Answer</label>
                            <input type="text" class="form-control" name="answer" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
