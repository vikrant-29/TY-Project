<?php
// admin_login.php

session_start();

// Check if the admin is already logged in
/*
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_panel.php'); // Redirect to admin panel if already logged in
    exit();
}
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'bank_management');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Admin login credentials (this should be in a more secure way in a real-world scenario)
    $admin_username = 'admin'; // Set admin username
    $admin_password = 'admin123'; // Set admin password

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify the credentials
    if ($username == $admin_username && $password == $admin_password) {
        // Set session to indicate the admin is logged in
        $_SESSION['admin_logged_in'] = true;

        // Redirect to the admin panel
        header('Location: admin_panel.php');
        exit();
    } else {
        $_SESSION['message'] = "Invalid username or password!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Bank Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>



    <div class="form-container">

        <h2>Admin Login</h2>
        <form method="POST" action="admin_login.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br><br>

            <?php
            // Display login error message if set
            if (isset($_SESSION['message'])) {
                echo "<p style='color: red;'>" . $_SESSION['message'] . "</p>";
                unset($_SESSION['message']);
            }
            ?>

            <button type="submit">Login</button>
        </form>
        <p>User? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>