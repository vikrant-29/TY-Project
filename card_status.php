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

// Query to fetch all card applications submitted by the user
$query = "SELECT * FROM card_applications WHERE user_id = '$user_id' ORDER BY submissionDate DESC";
$result = mysqli_query($conn, $query);
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
td{
    color: white;
}
</style>
<body>

<?php include('includes/navbar.php');?>

<div class="container">
    <h2>Your Card Application Status</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <!-- Table for displaying applications -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Card Type</th>
                    <th>Employment Status</th>
                    <th>Annual Income</th>
                    <th>Credit Score</th>
                    <th>Status</th>
                    <th>Submitted on</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($application = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $application['id']; ?></td>
                        <td><?php echo $application['cardType']; ?></td>
                        <td><?php echo $application['employmentStatus']; ?></td>
                        <td>₹<?php echo number_format($application['annualIncome'], 2); ?></td>
                        <td><?php echo $application['creditScore']; ?></td>
                        <td>
                            <?php 
                            if ($application['status'] == 'pending') {
                                echo '<span class="badge badge-warning">Pending</span>';
                            } elseif ($application['status'] == 'approved') {
                                echo '<span class="badge badge-success">Approved</span>';
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
        <p>You do not have any card applications submitted yet.</p>
    <?php endif; ?>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
