<?php
include('config.inc.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables for error messages
$eventErr = $scoreErr = $linkErr = "";
$event = $score = $link = $screenshots = "";
$submissionMessage = "";

if (isset($_POST['submit'])) {
    $event = $_POST['event'];
    $score = $_POST['score'];
    $link = $_POST['link'];
    $screenshots = $_POST['screenshots'];

    // Validate event
    if (empty($event)) {
        $eventErr = "Event/Website is required";
    }

    // Validate score
    if (empty($score) || !is_numeric($score)) {
        $scoreErr = "Score must be a valid number";
    }

    // Validate link
    if (empty($link)) {
        $linkErr = "Link to Profile is required";
    }

    // Check if there are no validation errors
    if (empty($eventErr) && empty($scoreErr) && empty($linkErr)) {
        // Retrieve the unique ID from the user's registration data
        $query = "SELECT unique_id FROM users WHERE id = '$user_id'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $unique_id = $row['unique_id'];

            // Insert the submission into the database
            $query = "INSERT INTO submissions (unique_id, event, score, link, screenshots) VALUES ('$unique_id', '$event', '$score', '$link', '$screenshots')";
            if ($conn->query($query) === TRUE) {
                $submissionMessage = "Submission successful!";
            } else {
                echo "Error: " . $query . "<br>" . $conn->error;
            }
        } else {
            echo "User not found or invalid user data.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Add Bootstrap CSS and JavaScript from CDNs -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Submit Event/Website</title>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mt-5 mb-3">Submit Event/Website</h2>
                <form method="post" action="submit">
                    <div class="form-group">
                        <label for="event">Event/Website</label>
                        <input type="text" class="form-control" name="event" id="event" placeholder="Event/Website" value="<?php echo $event; ?>" required>
                        <span class="text-danger"><?php echo $eventErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="score">Score</label>
                        <input type="number" class="form-control" name="score" id="score" placeholder="Score" value="<?php echo $score; ?>" required>
                        <span class="text-danger"><?php echo $scoreErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="link">Link to Profile</label>
                        <input type="url" class="form-control" name="link" id="link" placeholder="Link to Profile" value="<?php echo $link; ?>" required>
                        <span class="text-danger"><?php echo $linkErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="screenshots">Screenshots</label>
                        <input class="form-control" type="link" name="screenshots" id="screenshots" placeholder="Screenshots" value="<?php echo $screenshots; ?>">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
                <div class="mt-3">
                    <p class="text-success"><?php echo $submissionMessage; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
