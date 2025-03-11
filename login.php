<?php
ob_start();

session_set_cookie_params(1800);  // Set the cookie expiration time to 30 minutes (1800 seconds)
ini_set('session.gc_maxlifetime', 1800); // Set the session max lifetime to 30 minutes
session_start();

include('includes/header.html');
include('includes/connect.php');

// If form is submitted
if (isset($_POST['submit'])) {
    $nm = $_POST['user_nm'];
    $pss = $_POST['user_pss'];

    // Prepared statement to check if the user exists
    $query_1 = "SELECT userName, pass, id, photo, login_attempts, last_failed_attempt 
                FROM users 
                WHERE userName = ?";
    
    if ($stmt = $conn->prepare($query_1)) {
        // Bind the username to the prepared statement
        $stmt->bind_param("s", $nm);
        
        // Execute the query
        $stmt->execute();
        
        // Store the result
        $stmt->store_result();
        
        // Bind the results to variables
        $stmt->bind_result($db_userName, $db_pass, $db_id, $db_photo, $db_login_attempts, $db_last_failed_attempt);

        // Check if user exists
        if ($stmt->num_rows > 0) {
            // Fetch the result i.e. next of the header
            $stmt->fetch();

            // Check if user is locked out (if 3 attempts and 24 hours have not passed)
            if ($db_login_attempts >= 3) {
                $last_attempt = strtotime($db_last_failed_attempt);
                $current_time = time();

                // If the user is locked out for 24 hours
                if (($current_time - $last_attempt) < 86400) {
                    $_SESSION['error_msg'] = 'You have been locked out due to too many failed login attempts. Please try again after 24 hours.';
                    header('Location: login.php');
                    exit();
                } else {
                    // Reset the attempts if 24 hours have passed
                    $reset_sql = "UPDATE users SET login_attempts = 0 WHERE userName = ?";
                    if ($reset_stmt = $conn->prepare($reset_sql)) {
                        $reset_stmt->bind_param("s", $nm);
                        $reset_stmt->execute();
                    }
                }
            }

            // If the user is not locked out, check credentials
            if ($db_login_attempts < 3) {
                // Check the password
                if ($db_pass == $pss) {
                    // Reset login attempts on successful login
                    $reset_sql = "UPDATE users SET login_attempts = 0 WHERE userName = ?";
                    if ($reset_stmt = $conn->prepare($reset_sql)) {
                        $reset_stmt->bind_param("s", $nm);
                        $reset_stmt->execute();
                    }

                    // Store user information in session variables
                    $_SESSION['user_nm'] = $nm;
                    $_SESSION['id'] = $db_id;
                    $_SESSION['photo'] = $db_photo;

                    header('Location: u_dashboard.php');
                    exit();
                } else {
                    // Increment login attempt count and update the last failed attempt timestamp
                    $new_attempt_count = $db_login_attempts + 1;
                    $current_time = date('Y-m-d H:i:s');
                    $update_sql = "UPDATE users SET login_attempts = ?, last_failed_attempt = ? WHERE userName = ?";
                    if ($update_stmt = $conn->prepare($update_sql)) {
                        $update_stmt->bind_param("iss", $new_attempt_count, $current_time, $nm);
                        $update_stmt->execute();
                    }

                    $_SESSION['error_msg'] = 'Invalid username or password. You have ' . (3 - $new_attempt_count) . ' attempts left.';
                    header('Location: login.php');
                    exit();
                }
            }
        } else {
            $_SESSION['error_msg'] = 'User does not exist.';
            header('Location: login.php');
            exit();
        }
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
    <link rel="stylesheet" href="style/reg1.css">
    <title>User Login</title>

    <style>
        .neon-link {
            color: #39FF14;
            text-decoration: none;
            text-shadow: 0 0 5px #39FF14, 0 0 10px #39FF14, 0 0 20px #39FF14, 0 0 40px #00FF00, 0 0 60px #00FF00, 0 0 80px #00FF00, 0 0 100px #00FF00;
        }

        .neon-link:hover {
            color: rgb(148, 252, 130);
            font-weight: 400;
            text-decoration: none;
            text-shadow: 0 0 5px #39FF14, 0 0 10px #39FF14, 0 0 20px #39FF14, 0 0 40px #00FF00, 0 0 60px #00FF00, 0 0 80px #00FF00, 0 0 100px #00FF00;
            text-decoration: underline;
        }
    </style>
    <link rel="icon" href="bank_img/logo_fade.ico" type="image/x-icon">
</head>

<body>

    <div class="container my-5 shadow-lg p-3 mb-5 bg-transparent rounded">
        <!-- Check for any session error messages -->
        <?php
        if (isset($_SESSION['error_msg'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error_msg'] . "</div>";
            unset($_SESSION['error_msg']); // Clear the error message after showing it
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="md-3 form-group ">
                <label>username</label>
                <input type="text" class="form-control" name="user_nm" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="user_pss" required>
            </div>
            <div class="link-container">
                <a href="register.php" class="neon-link">New User?</a>
                <div></div>
                <a href="pass_recover.php" class="neon-link">Forgot Password?</a>
            </div>

            <input type="submit" value="login" name="submit" class="btn btn-primary">
        </form>
    </div>
</body>

</html>
