<?php

session_start();
require_once 'core/models.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <link rel="stylesheet" href="css/hrDashboard.css">
</head>
<body>
    <header>
        <h1>Welcome HR: <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    </header>

    <nav>
        <a href="jobPosts.php">Create Job Post</a>
        <a href="viewApplications.php">View Applications</a>
        <a href="messages.php">Messages</a> 
    </nav>

    <div class="container">
        <a href="core/handleForms.php?logoutAUser=1" class="logout-link">Logout</a>

        <h2>Your Job Posts</h2>
        <?php

        $jobPosts = getJobPosts($user_id);  
        if (!empty($jobPosts)) {
            echo "<ul>";
            foreach ($jobPosts as $job) {
                echo "<li><strong>" . htmlspecialchars($job['title']) . "</strong> - " . htmlspecialchars($job['description']) . "</li>";

                $query = "
                    SELECT a.id AS application_id, a.user_id, u.username 
                    FROM applications a
                    JOIN users u ON a.user_id = u.id
                    WHERE a.job_post_id = ? AND (a.status IS NULL OR a.status = 'pending')
                ";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$job['id']]);
                $pendingApplicants = $stmt->fetchAll();

                $query = "
                    SELECT a.user_id, u.username 
                    FROM applications a
                    JOIN users u ON a.user_id = u.id
                    WHERE a.job_post_id = ? AND a.status = 'accepted'
                ";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$job['id']]);
                $hiredApplicants = $stmt->fetchAll();

                if ($hiredApplicants) {
                    echo "<ul><li><strong>Hired Applicants:</strong></li>";
                    foreach ($hiredApplicants as $applicant) {
                        echo "<li>" . htmlspecialchars($applicant['username']) . "</li>";
                    }
                    echo "</ul>";
                }

                $query = "
                    SELECT a.user_id, u.username 
                    FROM applications a
                    JOIN users u ON a.user_id = u.id
                    WHERE a.job_post_id = ? AND a.status = 'rejected'
                ";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$job['id']]);
                $rejectedApplicants = $stmt->fetchAll();

                if ($rejectedApplicants) {
                    echo "<ul><li><strong>Rejected Applicants:</strong></li>";
                    foreach ($rejectedApplicants as $applicant) {
                        echo "<li>" . htmlspecialchars($applicant['username']) . "</li>";
                    }
                    echo "</ul>";
                }
            }
            echo "</ul>";
        } else {
            echo "<p>No job posts available.</p>";
        }
        ?>
    </div>
</body>
</html>