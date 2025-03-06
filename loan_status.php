<?php

session_start();
include('includes/header.html');
include('includes/connect.php');
// Check if the user is logged in
if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['id']; 

// Fetch loan application status
$query = "SELECT approval_status FROM loan_applications WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$status = $row ? $row['approval_status'] : 'No application found';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Status</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/u_dashboard.css">
    <!-- Custom CSS -->
    <style>
    .contain {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        margin-top: 30px;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 217, 255, 0.5); /* Neon blue shadow */
        backdrop-filter: blur(10px);
        text-align: center;
    }

    .h {
        color: #00d9ff; /* Neon blue color */
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0, 217, 255, 0.5); /* Neon glow effect */
        margin-bottom: 20px;
    }

    p {
        font-size: 18px;
        color: #d1f0ff; /* Light blue text */
    }

    p strong {
        color: #00d9ff; /* Neon blue for strong text */
    }

    .btn-primary {
        background-color: #00d9ff; /* Neon blue color */
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        color: black; /* Dark text for contrast */
        font-weight: bold;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: #ff0077; /* Neon pink on hover */
        box-shadow: 0 0 15px rgba(255, 0, 119, 0.8); /* Neon pink glow */
        color: white; /* White text on hover */
    }

    .fas {
        margin-right: 10px; /* Add spacing between icon and text */
    }
</style>
</head>
<body>
    <div class="contain">
        <h2 class=".h"><i class="fas fa-file-invoice"></i> Your Loan Application Status</h2>
        <p>Your current application status is: <strong><?php echo $status; ?></strong></p>
        <a href="loan.php" class="btn btn-primary"><i class="fas fa-hand-holding-usd"></i> Apply for a new loan</a>
    </div>
</body>
</html>