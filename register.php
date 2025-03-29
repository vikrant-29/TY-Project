<?php
ob_start();
include("includes/header.html");

session_start();
include('includes/connect.php');

if (isset($_POST['submit'])) {
    $userName = $_POST['userName'];
    $checkUserQuery = "SELECT * FROM users WHERE userName = '$userName'";
    $userResult = mysqli_query($conn, $checkUserQuery);

    if (mysqli_num_rows($userResult) > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.'); window.location.href = 'register.php';</script>";
    } else {
        // If the username is unique, proceed with storing the data in session variables
        $_SESSION['firstName'] = $_POST['firstName'];
        $_SESSION['lastName'] = $_POST['lastName'];
        $_SESSION['userName'] = $_POST['userName'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['PhoneNo'] = $_POST['PhoneNo'];
        //header('Location: user_data.php');
    }
}
ob_end_flush();
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
    <div class="container"><!-- my-5 bg-transparent -->
        <h4>Create Your New Account</h4>
        <form action="register.php" method="POST" class="form-group">
            <div class="row my-3">
                <div class="col">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="nm" name="firstName" onblur="valname(this.value)" required>
                    <small id="valname"> </small>
                </div>

                <div class="col">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lnm"  name="lastName"onblur="val_lname(this.value)" required>
                    <small id="val_lname"></small>
                </div>
            </div>

            <div class="row my-3">
                <div class="col">
                    <label for="userName">Username</label>
                    <input type="text" class="form-control" id="username" name="userName" onblur="val_unm(this.value)" required>
                    <small id="user_nm"></small>
                </div>

                <div class="col">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="passw" name="password" onkeyup="pass(this.value)" required>
                    <small id="pass"></small>
                </div>

                <div class="col">
                    <label for="password">Comfirm Password</label>
                    <input type="password" class="form-control" id="cpass" name="c_pass" onkeyup="con_pass(this.value)" required>
                    <small id="c_p"></small>
                </div>
            </div>

            <div class="row my-3">
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" onblur="email_val(this.value)" required>
                    <small id="email_val"></small>
                </div>

                <div class="col">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="PhoneNo" onkeyup="phone_no(this.value)" required>
                    <small id="phone_no"></small>
                </div>
            </div>
        
            <input type="submit" name="submit" value="Register" onclick="submit_form()"  class="btn btn-primary">

            <span id="nnn" style="color: yellow; font-size:0px;"></span>
        </form>
    </div>
</body>
<script src="scripts/val.js"></script>

</html>