<?php
include("includes/header.html");

session_start();
include('includes/connect.php');

if (isset($_POST['submit'])) {
    $userName = $_POST['userName'];
    $checkUserQuery = "SELECT * FROM users WHERE userName = '$userName'";
    $userResult = mysqli_query($conn, $checkUserQuery);

    if (mysqli_num_rows($userResult) > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.'); window.location.href = 'resgister.php';</script>";
    } else {
        // If the username is unique, proceed with storing the data in session variables
        $_SESSION['firstName'] = $_POST['firstName'];
        $_SESSION['lastName'] = $_POST['lastName'];
        $_SESSION['userName'] = $_POST['userName'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['PhoneNo'] = $_POST['PhoneNo'];
        header('location:user_data.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css?v=1.0">

    <link rel="stylesheet" href="style/reg1.css">
    <title>Registration Form</title>
</head>

<body>


    <div class="container my-5 bg-transparent">
        <h4>Create Your New Account</h4>
        <form action="resgister.php" method="POST" class="form-group">
            <div class="row my-3">
                <div class="col">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" name="firstName" required>
                </div>

                <div class="col">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" name="lastName" required>
                </div>
            </div>

            <div class="row my-3">
                <div class="col">
                    <label for="userName">Username</label>
                    <input type="text" class="form-control" name="userName" required>
                </div>

                <div class="col">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="row my-3">
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="col">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" name="PhoneNo" required>
                </div>
            </div>

            <input type="submit" name="submit" value="Register" class="btn btn-primary">
        </form>
    </div>

</body>

</html>