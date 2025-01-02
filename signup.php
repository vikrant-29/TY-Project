<?php
// signup.php

// Start session for message display
session_start();

// Check if the form is submitted
if (isset($_POST['register'])) {
    // Get form values
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Hash the password
    $email = $_POST['email'];
    $aadhar_number = $_POST['aadhar_number'];
    $phone_number = $_POST['phone_number'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'bank_management');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username already exists
    $check_username_sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($check_username_sql);

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Username already taken. Please choose another one.";
    } else {
        // Check if email already exists
        $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($check_email_sql);

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "Email already registered. Please use another email.";
        } else {
            // Check if aadhar_number already exists
            $check_aadhar_sql = "SELECT * FROM users WHERE aadhar_number = '$aadhar_number'";
            $result = $conn->query($check_aadhar_sql);

            if ($result->num_rows > 0) {
                $_SESSION['message'] = "Aadhar number already registered. Please use a different Aadhar number.";
            } else {
                // Check if phone_number already exists
                $check_phone_sql = "SELECT * FROM users WHERE phone_number = '$phone_number'";
                $result = $conn->query($check_phone_sql);

                if ($result->num_rows > 0) {
                    $_SESSION['message'] = "Phone number already registered. Please use another phone number.";
                } else {
                    // If no duplicates, insert new user
                    $hashed_password = $password; // Hash the password
                    $sql = "INSERT INTO users (name, username, password, email, aadhar_number, phone_number, balance) 
                            VALUES ('$name', '$username', '$hashed_password', '$email', '$aadhar_number', '$phone_number', 0.00)";
                    
                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['message'] = "Account registered successfully! Please wait for admin approval.";
                        header("Location: login.php");
                    } else {
                        $_SESSION['message'] = "Error: " . $conn->error;
                    }
                }
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Bank Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <h2>Create New Account</h2>
    <form action="signup.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="aadhar_number" placeholder="Aadhar Number (12 digits)" required pattern="\d{12}" title="Please enter a valid 12-digit Aadhar number">
        <input type="text" name="phone_number" placeholder="Phone Number" required pattern="\d{10,15}" title="Please enter a valid phone number (10 to 15 digits)">
        <button type="submit" name="register">Register</button>
    </form>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
