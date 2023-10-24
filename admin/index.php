<?php
include('../config.inc.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit();
}

// Check if the export all button is clicked
if (isset($_POST['export_all'])) {
    // Fetch all submissions
    $export_query = "SELECT id, unique_id, event, score, link, screenshots, DATE_FORMAT(added_time, '%l:%i %p, %e %M %Y') AS formatted_time, status FROM submissions";
    $export_result = $conn->query($export_query);

    // Create and download CSV file
    $filename = "all_submissions.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Unique ID', 'Event', 'Score', 'Link', 'Screenshots', 'Added Time', 'Status'));

    while ($row = $export_result->fetch_assoc()) {
        fputcsv($output, array(
            $row['id'],
            $row['unique_id'],
            $row['event'],
            $row['score'],
            $row['link'],
            $row['screenshots'],
            $row['formatted_time'],
            $row['status']
        ));
    }

    fclose($output);
    exit(); // End the script to prevent rendering the page
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
        <form method="post" action="index.php">
            <div class="list-group">
                <a href="users" class="list-group-item list-group-item-action">View Registered Users</a>
                <a href="ctf" class="list-group-item list-group-item-action">Manage Submissions</a>
                <button type="submit" class="list-group-item list-group-item-action" name="export_all">Export All Submissions as CSV</button>
                <a href="logout" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </form>
    </div>

    <!-- Add Bootstrap JS at the end of your HTML -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
