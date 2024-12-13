<?php
session_start();
require_once 'core/models.php'; // Include the model file for database functions

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'applicant') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <link rel="stylesheet" href="css/applicantsDashboard.css">

</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome Applicant: <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p class="welcome-message">Your custom dashboard where you can explore job listings, monitor your applications, and view messages.</p>
        
        <a href="jobListings.php">View Job Listings</a>
        <a href="myApplications.php">My Applications</a>
        <a href="applicantsMessages.php">Messages</a> <!-- Link to the New Messages Page -->
        <a href="core/handleForms.php?logoutAUser=1" class="logout-link">Logout</a>

        <h2>Your Dashboard</h2>
        <!-- Add any other dashboard content here -->
    </div>
</body>
</html>