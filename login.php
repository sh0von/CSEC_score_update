<?php
include('config.inc.php');

// Initialize the error message variable
$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: index");
    exit();
}

if (isset($_POST['login'])) {
    $cuet_id = $_POST['cuet_id'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE cuet_id = '$cuet_id'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}

$conn->close();
?><!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Bootstrap CSS and JavaScript from CDNs -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>User Login</title>
</head>
<body>
    <div class="container" style="padding-top:100px">
        <h2 class="text-center">Login</h2>
        <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-4">
                    <!-- Display error message if present -->
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="login" class="mx-auto">
                        <div class="form-group">
                            <input type="text" name="cuet_id" class="form-control" placeholder="CUET ID" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <!-- Add the "Register" link here -->
                    <p class="text-center mt-3">
                        Don't have an account? <a href="register">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
