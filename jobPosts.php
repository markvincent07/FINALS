<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: ../login.php");
    exit();
}
require_once 'core/dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insert the job post into the database
    $sql = "INSERT INTO job_posts (title, description, created_by) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $description, $_SESSION['user_id']]);

    header("Location: hrDashboard.php"); // Redirect after successful job post creation
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Post</title>
    <link rel="stylesheet" href="css/jobPosts.css">

</head>
<body>
    <header>
        <h1>Create a New Job Post</h1>
    </header>

    <div class="container">
        <form method="POST" action="jobPosts.php">
            <label for="title">Job Title:</label>
            <input type="text" name="title" required>

            <label for="description">Job Description:</label>
            <textarea name="description" required></textarea>

            <button type="submit">Create Job Post</button>
        </form>

        <div class="back-link">
            <a href="hrDashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>