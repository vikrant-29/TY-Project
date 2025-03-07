<?php

ob_start();

session_start();
include('includes/header.html');
include('includes/connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

$firstName = $_SESSION['nm'];
$lastName = $_SESSION['lastName'];
$userName = $_SESSION['user_nm'];
$balance = $_SESSION['balance'];
$email = $_SESSION['email'];
$phoneNumber = $_SESSION['mob'];
$user_id = $_SESSION['id'];

// Fetch all loan applications for the user
$query = "SELECT * FROM loan_applications WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

ob_end_flush();
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
    
    </style>
</head>
<body>

<?php include('includes/navbar.php');?>


    <div class="container">
        <h2 class="h"><i class="fas fa-file-invoice"></i> Your Loan Application Status</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Loan Type</th>
                        <th>Loan Amount</th>
                        <th>Loan Purpose</th>
                        <th>Application Status</th>
                        <th>Submission Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($application = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $application['id']; ?></td>
                            <td><?php echo $application['loanType']; ?></td>
                            <td>₹<?php echo number_format($application['loanAmount'], 2); ?></td>
                            <td><?php echo $application['loanPurpose']; ?></td>
                            <td>
                                <?php
                                if ($application['approval_status'] == 'Approved') {
                                    echo '<span class="badge badge-success">Approved</span>';
                                } elseif ($application['approval_status'] == 'Pending') {
                                    echo '<span class="badge badge-warning">Pending</span>';
                                } else {
                                    echo '<span class="badge badge-danger">Rejected</span>';
                                }
                                ?>
                            </td>
                            <td><?php echo $application['submissionDate']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not submitted any loan applications yet.</p>
        <?php endif; ?>
        
        <a href="loan.php" class="btn btn-primary"><i class="fas fa-hand-holding-usd"></i> Apply for a new loan</a>
    </div>
</body>
</html>
