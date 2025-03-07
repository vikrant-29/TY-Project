<?php

ob_start();

session_start();
include('includes/connect.php');
include('includes/header.html');

if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

$account_number = $_SESSION['acc_no'];
$balance = $_SESSION['balance'];
$email = $_SESSION['email'];
$name = $_SESSION['nm'];
$mobile_number = $_SESSION['mob'];
$user_id = $_SESSION['id'];
$profile_img = $_SESSION['photo'];

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Bank Management</title>
    <link rel="stylesheet" href="style/u_dashboard.css">
     <!-- Font Awesome for Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<?php include('includes/navbar.php');?>



<div id="profile">
    <div class="profile-card">
        <img src="uimg/<?php echo $profile_img; ?>" alt="Profile Background" class="profile-bg">
        <div class="profile-content">
            <h3><?php echo htmlspecialchars($name); ?></h3>
            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><i class="fas fa-phone-alt"></i> <strong>Mobile:</strong> <?php echo htmlspecialchars($mobile_number); ?></p>
            <p><i class="fas fa-wallet"></i> <strong>Balance:</strong> ₹<?php echo number_format($balance, 2); ?></p>
            <p><i class="fas fa-credit-card"></i> <strong>Account No.:</strong> <?php echo htmlspecialchars($account_number); ?></p>
        </div>
    </div>
</div>



    <div class="cards-container">
        <div class="card">
            <img src="bank_img/cards.png" alt="Notifications" class="card-image">
            <h3>Apply For Cards</h3>
            <p>Get Your Personal Cards</p>
            <a href="cards.php">Apply</a>
        </div>
        <div class="card">
            <img src="bank_img/contact.jpg" alt="Contact Us" class="card-image">
            <h3>Feedback</h3>
            <p>If you have any queries or something to say, feel free to reach out to us.</p>
            <a href="feedback.php">Submit Feedback</a>
        </div>

        <div class="card">
            <img src="bank_img/summary.jpg" alt="Account Summary" class="card-image">
            <h3>Account Summary</h3>
            <p>View details of your account and recent activities.</p>
            <a href="account_statement.php">View Summary</a>
        </div>
        <div class="card">
            <img src="bank_img/transfer.jpg" alt="Transfer Money" class="card-image">
            <h3>Transfer Money</h3>
            <p>Send funds to another account.</p>
            <a href="transaction.php">Transfer Now</a>
        </div>
        <div class="card">
            <img src="bank_img/savings.png" alt="Invest Money" class="card-image">
            <h3>Invest Money</h3>
            <p>Invest your money to grow</p>
            <a href="invest.php">Invest Now</a>
        </div>
        <div class="card">
            <img src="bank_img/loan.jpg" alt="Loan Money" class="card-image">
            <h3>Apply for Loan</h3>
            <p>Get Lonas for different purpose</p>
            <a href="loan.php">Get Loan</a>
        </div>
    </div>
    <?php //endif; 
    ?>
    </div>

    <?php
    $conn->close();
    ?>
</body>
</html>