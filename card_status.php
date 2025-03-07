<?php
session_start();
include('includes/connect.php');
include('includes/header.html');

$account_number = $_SESSION['acc_no'];
$balance = $_SESSION['balance'];
$email = $_SESSION['email'];
$name = $_SESSION['nm'];
$mobile_number = $_SESSION['mob'];
$user_id = $_SESSION['id'];
$profile_img = $_SESSION['photo'];

if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

// Fetching user details from the session
$user_id = $_SESSION['id'];  // This will link the application to the user

// Query to fetch the most recent card application submitted by the user
$query = "SELECT * FROM card_applications WHERE user_id = '$user_id' ORDER BY submissionDate DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$application = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Application Status</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/u_dashboard.css">
</head>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/Bank_Management_System/bank_img/back3.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        color: #d1f0ff; /* Light blue text */
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 217, 255, 0.5); /* Neon blue shadow */
        backdrop-filter: blur(10px);
    }

    h2 {
        color: #00d9ff; /* Neon blue color */
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0, 217, 255, 0.5); /* Neon glow effect */
        margin-bottom: 20px;
    }

    .card {
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
        border: 1px solid #00d9ff; /* Neon blue border */
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 217, 255, 0.3); /* Neon blue shadow */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(255, 0, 119, 0.7); /* Neon pink shadow on hover */
    }

    .card-body {
        padding: 20px;
    }

    .card-title {
        color: #00d9ff; /* Neon blue color */
        font-size: 24px;
        margin-bottom: 15px;
    }

    .card p {
        color: #d1f0ff; /* Light blue text */
        font-size: 16px;
        margin: 10px 0;
    }

    .card p strong {
        color: #00d9ff; /* Neon blue for strong text */
    }

    .badge {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-warning {
        background-color: #ffc107; /* Yellow for pending status */
        color: black;
    }

    .badge-success {
        background-color: #28a745; /* Green for approved status */
        color: white;
    }

    .badge-danger {
        background-color: #dc3545; /* Red for rejected status */
        color: white;
    }

    p {
        color: #d1f0ff; /* Light blue text */
        font-size: 16px;
    }
</style>
<body>

<?php include('includes/navbar.php');?>

    <div class="container">
        <h2>Your Card Application Status</h2>
        <?php if ($application): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Application ID: <?php echo $application['id']; ?></h5>
                    <p><strong>Card Type:</strong> <?php echo $application['cardType']; ?></p>
                    <p><strong>Employment Status:</strong> <?php echo $application['employmentStatus']; ?></p>
                    <p><strong>Annual Income:</strong> ₹<?php echo number_format($application['annualIncome'], 2); ?></p>
                    <p><strong>Credit Score:</strong> <?php echo $application['creditScore']; ?></p>
                    <p><strong>Status:</strong> 
                        <?php 
                        if ($application['status'] == 'pending') {
                            echo '<span class="badge badge-warning">Pending</span>';
                        } elseif ($application['status'] == 'approved') {
                            echo '<span class="badge badge-success">Approved</span>';
                        } else {
                            echo '<span class="badge badge-danger">Rejected</span>';
                        }
                        ?>
                    </p>
                    <p><strong>Submitted on:</strong> <?php echo $application['submissionDate']; ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>You do not have any card applications submitted yet.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
