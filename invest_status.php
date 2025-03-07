<?php
ob_start();

session_start();
include('includes/connect.php');
include('includes/header.html');

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
$user_id = $_SESSION['id'];  // This links the application to the user

// Query to fetch all applications submitted by the user
$query = "SELECT * FROM investment_applications WHERE user_id = '$user_id' ORDER BY submissionDate DESC";
$result = mysqli_query($conn, $query);
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Application Status</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/u_dashboard.css">
</head>
<style>

    p,td {
        color: #d1f0ff; /* Light blue text */
        font-size: 16px;
    }
</style>
<body>

<?php include('includes/navbar.php');?>


<div class="container">
    <h2>Your Investment Applications</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <!-- Display all applications in a table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Investment Type</th>
                    <th>Amount</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($application = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $application['id']; ?></td>
                        <td><?php echo $application['investmentType']; ?></td>
                        <td>₹<?php echo number_format($application['investmentAmount'], 2); ?></td>
                        <td><?php echo $application['investmentDuration']; ?> years</td>
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
        <p>You have not submitted any investment applications yet.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
