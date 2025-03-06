<?php
session_start();
include('includes/header.html');
include('includes/connect.php');


if (isset($_POST['submit'])) {
    $nm = $_POST['user_nm'];
    
    // Query to get the security question and answer from the database
    $query_1 = "SELECT sec_que, que_ans FROM users WHERE userName = '$nm'";
    $myres = mysqli_query($conn, $query_1);

    if (mysqli_num_rows($myres) > 0) {
        $row = mysqli_fetch_assoc($myres);
        $_SESSION['sec_que'] = $row['sec_que']; // Security question
        $_SESSION['que_ans'] = $row['que_ans']; // Security answer
        $sec_que=$_SESSION['sec_que'];
        $_SESSION['username'] = $nm; 
        $que_ans = $_SESSION['que_ans'];
    } else {
        echo "<script>alert('Username not found. Please check and try again.')</script>";
    }
}

if (isset($_POST['verify_answer'])) {
    $user_answer = $_POST['answer'];
    // Retrieve the correct answer from the session
    if (isset($_SESSION['que_ans']) && $user_answer == $_SESSION['que_ans']) {
        // Answer is correct, fetch the password from the database

        $username = $_SESSION['username'];  // Get the username from session

        // Query to get the user's password based on the username
        $query_2 = "SELECT pass FROM users WHERE userName = '$username'";
        $myres_2 = mysqli_query($conn, $query_2);
        
        if (mysqli_num_rows($myres_2) > 0) {
            $row_2 = mysqli_fetch_assoc($myres_2);
            echo "<script>alert('Your password is: " . $row_2['pass'] . "');</script>";
        } else {
            echo "<script>alert('Error retrieving password. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Incorrect answer. Please try again.');</script>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Password Recovery</title>
    <link rel="stylesheet" href="style/u_dashboard.css">
    <style>

    h2 {
        color: #00d9ff; /* Neon blue color */
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0, 217, 255, 0.5); /* Neon glow effect */
        margin-bottom: 20px;
        text-align: center;
    }

    form {
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 217, 255, 0.5); /* Neon blue shadow */
        backdrop-filter: blur(10px);
        max-width: 400px;
        width: 100%;
    }

    form div {
        margin-bottom: 15px;
    }

    form label {
        display: block;
        font-weight: bold;
        color: #00d9ff; /* Neon blue color */
        margin-bottom: 5px;
    }

    form input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #00d9ff; /* Neon blue border */
        border-radius: 5px;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
        color: #d1f0ff; /* Light blue text */
        font-size: 16px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    form input[type="text"]:focus {
        border-color: #ff0077; /* Neon pink on focus */
        box-shadow: 0 0 10px rgba(255, 0, 119, 0.5); /* Neon pink glow */
        outline: none;
    }

    form input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #00d9ff; /* Neon blue color */
        border: none;
        border-radius: 5px;
        color: black; /* Dark text for contrast */
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    form input[type="submit"]:hover {
        background-color: #ff0077; /* Neon pink on hover */
        box-shadow: 0 0 15px rgba(255, 0, 119, 0.8); /* Neon pink glow */
        color: white; /* White text on hover */
    }
</style>

</head>
<body>
    <h2>Password Recovery</h2>
    
    <!-- Step 1: Enter Username -->
    <?php if (!isset($sec_que)): ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div>
            <label>Enter Username</label>
            <input type="text" name="user_nm" required>
        </div>
        <div>
            <input type="submit" value="Submit" name="submit">
        </div>
    </form>
    <?php else: ?>
        <!-- Step 2: Answer the security question -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div>
                <label>Security Question: <?php echo $sec_que; ?></label>
                <input type="text" name="answer">
            </div>
            <div>
                <input type="submit" name="verify_answer" value="Submit Answer">
            </div>
        </form>
    <?php endif; ?>
</body>
</html>
