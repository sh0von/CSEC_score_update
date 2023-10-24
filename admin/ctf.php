<?php
include('../config.inc.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit();
}

// Initialize error messages array
$errors = [];

// Handle updating submission statuses
if (isset($_POST['update_status'])) {
    $submission_ids = $_POST['submission_ids'];
    $new_status = $_POST['new_status'];

    if (!empty($submission_ids)) {
        // Use the IN clause to update multiple submissions at once
        $submission_ids_list = implode(',', $submission_ids);
        $update_query = "UPDATE submissions SET status = '$new_status' WHERE id IN ($submission_ids_list)";
        
        if ($conn->query($update_query)) {
            // Success message
            $successMessage = "Status updated successfully.";
        } else {
            // Add error to the errors array
            $errors[] = "Error updating status: " . $conn->error;
        }
    } else {
        // Add error to the errors array
        $errors[] = "No submissions selected for status update.";
    }
}
$submission_ids = array(); // Initialize as an empty array

// Check if the export button is clicked
$submission_ids = isset($_POST['submission_ids']) ? $_POST['submission_ids'] : array(); // Initialize as an empty array

// Check if the export button is clicked
if (isset($_POST['export'])) {
    if (empty($submission_ids)) {
        $error_message = "No data selected for export.";
    } else {
        // Fetch selected submissions
        $submission_ids_list = implode(',', $submission_ids);
        $export_query = "SELECT id, unique_id, event, score, link, screenshots, DATE_FORMAT(added_time, '%l:%i %p, %e %M %Y') AS formatted_time, status FROM submissions WHERE id IN ($submission_ids_list)";
        $export_result = $conn->query($export_query);

        // Create and download CSV file
        $filename = "selected_submissions.csv";
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
}

// Pagination variables
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Admin is logged in, display the content with pagination and descending order
$order_column = "unique_id"; // Change this to the column you want to order by
$query = "SELECT id, unique_id, event, score, link, screenshots, DATE_FORMAT(added_time, '%l:%i %p, %e %M %Y') AS formatted_time, status FROM submissions ORDER BY $order_column DESC LIMIT $offset, $records_per_page";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Submissions</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Admin Panel - Submissions</h2>
        <!-- Display errors if there are any -->
        <?php
if (isset($error_message)) {
    echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
}
?>

        <!-- Display success message if there is one -->
        <?php if (isset($successMessage)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="ctf">
            <div class="table-responsive">
                <!-- ... Rest of the HTML code ... -->

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"><input type="checkbox" id="select-all"> Select All</th>
                    <th scope="col">Status</th>
                    <th scope="col">Unique ID</th>
                    <th scope="col">Event</th>
                    <th scope="col">Score</th>
                    <th scope="col">Link</th>
                    <th scope="col">Screenshots</th>
                    <th scope="col">Added Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><input type="checkbox" class="submission-checkbox" name="submission_ids[]" value="<?= $row['id'] ?>"></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['unique_id'] ?></td>
                        <td><?= $row['event'] ?></td>
                        <td><?= $row['score'] ?></td>
                        <td><a href="<?= $row['link'] ?>" target="_blank"><?= $row['link'] ?></a></td>
                        <td><a href="<?= $row['screenshots'] ?>" target="_blank"><?= $row['screenshots'] ?></a></td>
                        <td><?= $row['formatted_time'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>   </div>
            <div class="row justify-content-center">
                <div class="form-group col-md-4">
                    <select class="form-control" name="new_status" id="new_status">
                        <option value="done">Done</option>
                        <option value="not done">Not Done</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary btn-block" name="update_status">Update Status</button>
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-success btn-block" name="export">Export to CSV</button>
                </div>
            </div>
        </form>
<!-- JavaScript for "Select All" functionality -->
<script>
    const selectAllCheckbox = document.getElementById('select-all');
    const submissionCheckboxes = document.querySelectorAll('.submission-checkbox');

    selectAllCheckbox.addEventListener('change', function () {
        submissionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    submissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            }
        });
    });
</script>


        <!-- Pagination Links -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                $total_records_query = "SELECT COUNT(*) AS total_records FROM submissions";
                $total_records_result = $conn->query($total_records_query);
                $total_records = $total_records_result->fetch_assoc()['total_records'];
                $total_pages = ceil($total_records / $records_per_page);

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='page-item'><a class='page-link' href='ctf?page=$i'>$i</a></li>";
                }
                ?>
            </ul>
        </nav>
    </div>

    <!-- Add Bootstrap JS at the end of your HTML -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>