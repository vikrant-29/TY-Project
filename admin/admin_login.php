<?php
include('../includes/header.html');
include('../includes/connect.php');

if (isset($_POST['submit'])) {
    // Sanitize user inputs to prevent SQL injection
    $nm = mysqli_real_escape_string($conn, $_POST['admin_nm']);
    $pss = mysqli_real_escape_string($conn, $_POST['admin_pss']);

    // Create the SQL query
    $query_1 = "SELECT username, pass FROM admin WHERE username = '$nm' AND pass = '$pss'";

    // Execute the query
    $myres = mysqli_query($conn, $query_1);

    // Check if any row is returned
    if (mysqli_num_rows($myres) > 0) {
        // Redirect to the dashboard if login is successful
        header('location:dashboard.php');
        exit; // Make sure to exit to prevent further code execution
    } else {
        // Display an error message if credentials are incorrect
        echo "<script>alert('Username/Password incorrect')</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Admin Login</title>
</head>
<body>
    <style>
        body {
            background-image: linear-gradient(to bottom right, #00f260, #0575e6);
            background-attachment: fixed; /* Keep the background fixed when scrolling */
        }

        .container {
            width: 600px;
        }
    </style>
    <div class="container my-5 shadow-lg p-3 mb-5 bg-white rounded">
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
