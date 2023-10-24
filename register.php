<?php
include('config.inc.php');

// Initialize variables for error messages and user input
$emailErr = $cuetIdErr = $nameErr = $passwordErr = "";
$emailInput = $cuetIdInput = $nameInput = "";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $cuet_id = $_POST['cuet_id'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $emailInput = $email; // Preserve valid input
    }

    // Validate CUET ID
    if (strlen($cuet_id) !== 7) {
        $cuetIdErr = "CUET ID must be 7 characters long";
    } else {
        $cuetIdInput = $cuet_id; // Preserve valid input
    }

    // Validate name
    if (empty($name)) {
        $nameErr = "Name is required";
    } else {
        $nameInput = $name; // Preserve valid input
    }

    // Validate password
    if (strlen($_POST['password']) < 6) {
        $passwordErr = "Password must be at least 6 characters long";
    }

    // Check if there are no validation errors
    if (empty($emailErr) && empty($cuetIdErr) && empty($nameErr) && empty($passwordErr)) {
        // Generate a unique ID like CSEC-0001
        $query = "SELECT MAX(id) as max_id FROM users";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $max_id = $row['max_id'] + 1;
        $unique_id = "CSEC-" . sprintf("%04d", $max_id);

        $query = "INSERT INTO users (email, cuet_id, name, unique_id, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $email, $cuet_id, $name, $unique_id, $password);

        if ($stmt->execute()) {
            header("Location: index");
            exit();
        } else {
            echo "Error: " . $query . "<br>" . $stmt->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Bootstrap CSS and JavaScript from CDNs -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>User Registration</title>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body">
                        <h2 class="card-title text-center">Register</h2>
                        <form method="post" action="register">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email" required value="<?php echo $emailInput; ?>">
                                <span class="text-danger"><?php echo $emailErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="cuet_id" placeholder="CUET ID" required value="<?php echo $cuetIdInput; ?>">
                                <span class="text-danger"><?php echo $cuetIdErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="Name" required value="<?php echo $nameInput; ?>">
                                <span class="text-danger"><?php echo $nameErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                <span class="text-danger"><?php echo $passwordErr; ?></span>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                        </form>
                    </div>
                </div>
                <!-- Add the "Login" link here -->
                <p class="text-center mt-3">
                    Already have an account? <a href="login">Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
