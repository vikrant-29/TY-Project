<?php
ob_start();

session_set_cookie_params(1800);  // Set session cookie timeout to 30 minutes (1800 seconds)
ini_set('session.gc_maxlifetime', 1800); // Set session lifetime to 30 minutes
session_start(); // Start the session

include('../includes/header.html');
include('../includes/connect.php');

if (isset($_POST['submit'])) {

    $nm = $_POST['admin_nm'];
    $pss = $_POST['admin_pss'];

    // Use prepared statement to prevent SQL injection
    $query_1 = "SELECT username, _role, pass, id, _name FROM admin WHERE username = ? AND pass = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($query_1)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("ss", $nm, $pss);

        // Execute the statement
        $stmt->execute();

        // Store the result
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            // Bind the result variables
            $stmt->bind_result($db_username, $db_role, $db_pass, $db_id, $db_name);

            // Fetch the result
            $stmt->fetch();

            // Set session variables
            $_SESSION['ad_login'] = 1;
            $_SESSION['unm'] = $db_username;
            $_SESSION['role'] = $db_role;
            $_SESSION['name'] = $db_name;
            $_SESSION['id'] = $db_id;

            // Redirect to the dashboard
            header('location: dashboard.php');
            exit;
        } else {
            $_SESSION['ad_login'] = 0;
            echo "<script>alert('Username/Password incorrect')</script>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

ob_end_flush();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/reg1.css">
    <title>Admin Login</title>
</head>

<body>
    <div class="container my-5 shadow-lg p-3 mb-5 bg-transparent rounded">
        <form action="admin_login.php" method="POST">
            <div class="md-3 form-group">
                <label for="admin_nm">Username</label>
                <input type="email" class="form-control" name="admin_nm" id="admin_nm" required>
            </div>
            <div class="form-group">
                <label for="admin_pss">Password</label>
                <input type="password" class="form-control" name="admin_pss" id="admin_pss" required>
            </div>

            <input type="submit" value="Login" name="submit" class="btn btn-primary">
        </form>
    </div>
</body>

</html>
