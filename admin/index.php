<?php
include('../config.inc.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Admin Panel - Dashboard</h2>
        <div class="list-group">
            <a href="users" class="list-group-item list-group-item-action">View Registered Users</a>
            <a href="ctf" class="list-group-item list-group-item-action">Manage Submissions</a>
            <a href="logout" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </div>

    <!-- Add Bootstrap JS at the end of your HTML -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
