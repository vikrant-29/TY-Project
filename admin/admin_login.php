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

    // Fix the SQL query: Removed the extra comma after _name
    $query_1 = "SELECT username, _role, pass, id, _name FROM admin WHERE username = '$nm' AND pass = '$pss'";

    $myres = mysqli_query($conn, $query_1);

    if (mysqli_num_rows($myres) > 0) {
        // Fetch the result row
        $row = mysqli_fetch_assoc($myres);

        // Set session variables
        $_SESSION['ad_login'] = 1;
        $_SESSION['unm'] = $nm;
        $_SESSION['role'] = $row['_role'];  // Add _role from query result
        $_SESSION['name'] = $row['_name'];  // Add _name from query result
        $_SESSION['id'] = $row['id'];      // Add id from query result

        // Redirect to dashboard
        header('location: dashboard.php');
        exit;
    } else {
        $_SESSION['ad_login'] = 0;
        echo "<script>alert('Username/Password incorrect')</script>";
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