<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: admin_login.php');
    exit();
}

include('../includes/header.html');
include('../includes/connect.php');
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Admin Dashboard</title>
</head>

<body>
    <style>
        body {
            background-image: linear-gradient(to bottom right, #00f260, #0575e6);
            background-attachment: fixed; /* Keep the background fixed when scrolling */
        }
        .container {
            width: 800px;
            margin-top: 50px;
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logout-btn {
            margin-top: 20px;
        }
    </style>

    <div class="container my-5 shadow-lg p-3 mb-5 bg-white rounded">
        <div class="dashboard-header">
            <h1>Welcome to the Admin Dashboard</h1>
            <p>You have successfully logged in!</p>
        </div>

        <!-- Add your dashboard content here -->
        <div class="content">
            <p>This is where you can manage your website's content, users, and settings.</p>
        </div>

        <!-- Logout Button -->
        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger logout-btn">Logout</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>

</html>