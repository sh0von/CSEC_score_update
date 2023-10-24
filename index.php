<?php
include('config.inc.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get the user's data, submission count, and submissions
$query = "SELECT u.*, COUNT(s.id) AS submission_count, MAX(s.added_time) AS last_submission_time
          FROM users u
          LEFT JOIN submissions s ON u.unique_id = s.unique_id
          WHERE u.id = '$user_id'
          GROUP BY u.id";

$result = $conn->query($query);
$user = $result->fetch_assoc();

// Query to get all user's submissions
$submissionsQuery = "SELECT * FROM submissions WHERE unique_id = '" . $user['unique_id'] . "'";
$submissionsResult = $conn->query($submissionsQuery);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Profile</title>
</head>
<body class="bg-light">
    <div class="container" style="padding-bottom: 30px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-body">
                        <h2 class="card-title text-center">User Profile</h2>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Unique ID:</strong> <?php echo $user['unique_id']; ?></p>
                                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                                <p><strong>CUET ID:</strong> <?php echo $user['cuet_id']; ?></p>
                                <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Submissions:</strong> <?php echo $user['submission_count']; ?></p>
                                <p><strong>Last Submission Time:</strong> <?php echo $user['last_submission_time']; ?></p>
                            </div>
                        </div>
                        <div class="text-center mt-4">
    <a href="submit" class="btn btn-primary">Submit your updated Score</a>
</div>

                        <h4 class="mt-4">Submission Details</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Score</th>
                                        <th>Link</th>
                                        <th>Screenshots</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $submissionsResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['event'] . "</td>";
                                        echo "<td>" . $row['score'] . "</td>";
                                        echo "<td><a href='" . $row['link'] . "' target='_blank'>View Link</a></td>";
                                        echo "<td><a href='" . $row['screenshots'] . "' target='_blank'>View Screenshots</a></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Logout Button -->
                        <form method="post" action="logout">
                            <button class="btn btn-danger btn-block mt-4" type="submit" name="logout">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript and jQuery if needed -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
