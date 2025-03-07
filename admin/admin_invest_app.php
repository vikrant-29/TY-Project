<?php
ob_start();

session_start();
include('../includes/header.html');
include('../includes/connect.php');

// Admin check (you can add an actual admin check here if necessary)
if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch investment applications
$query = "SELECT * FROM investment_applications";
$result = mysqli_query($conn, $query);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Applications - Admin</title>

    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
    <!-- Navbar -->
    <?php include('../includes/admin_navbar.php');?>

    
    <div>
        <h2>Investment Applications</h2>

        <!-- Display investment applications -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>User Name</th>
                        <th>Investment Type</th>
                        <th>Amount (₹)</th>
                        <th>Duration (Years)</th>
                        <th>Risk Appetite</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['userName']; ?></td>
                            <td><?php echo $row['investmentType']; ?></td>
                            <td><?php echo $row['investmentAmount']; ?></td>
                            <td><?php echo $row['investmentDuration']; ?></td>
                            <td><?php echo $row['riskAppetite']; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
                                <!-- Approve and Reject buttons -->
                                <form action="admin_invest_app.php" method="POST">
                                    <input type="hidden" name="app_id" value="<?php echo $row['id']; ?>">
                                    <?php if ($row['status'] == 'pending'): ?>
                                        <button type="submit" name="approve" class="btn btn-success btn-approve">Approve</button>
                                        <button type="submit" name="reject" class="btn btn-danger btn-reject">Reject</button>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Already Processed</span>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No investment applications found.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php
// Handle approve and reject actions
if (isset($_POST['approve']) || isset($_POST['reject'])) {
    $app_id = $_POST['app_id'];
    $status = isset($_POST['approve']) ? 'approved' : 'rejected';

    // Update the status in the database
    $update_query = "UPDATE investment_applications SET status='$status' WHERE id='$app_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Application $status successfully.'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'dashboard.php';</script>";
    }
}
?>
