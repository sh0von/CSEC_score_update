<?php
include('../config.inc.php');

// Check if the admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index");
    exit();
}

if (isset($_POST['admin_login'])) {
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];

    // Check admin credentials from the "admin" table
    $query = "SELECT * FROM admin WHERE username = '$admin_username'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($admin_password, $admin['password'])) {
            // Admin login successful, set a session variable
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: index"); // Redirect to the admin panel
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container" style="padding-top:50px">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">Admin Login</h2>
                <form method="post" action="login">
                    <div class="form-group">
                        <input type="text" class="form-control" name="admin_username" placeholder="Admin Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="admin_password" placeholder="Admin Password" required>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" name="admin_login" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
