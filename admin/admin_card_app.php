<?php
session_start();
include('../includes/header.html');
include('../includes/connect.php');  // Update this path if needed

// Check if the admin is logged in
if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all pending card applications
$query = "SELECT * FROM card_applications WHERE status = 'pending'";
$result = mysqli_query($conn, $query);

// Handle approve or reject action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $cardId = $_GET['id'];
    $action = $_GET['action'];

    // Update approval status
    if ($action == 'approve') {
        $updateQuery = "UPDATE card_applications SET status = 'approved' WHERE id = '$cardId'";
        if (mysqli_query($conn, $updateQuery)) {
            // Optional: Log this approval action if needed
            echo "<script>alert('Card application approved successfully.'); window.location.href = 'admin_card_app.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to approve card application.'); window.location.href = 'admin_card_app.php';</script>";
        }
    } elseif ($action == 'reject') {
        $updateQuery = "UPDATE card_applications SET status = 'rejected' WHERE id = '$cardId'";
        if (mysqli_query($conn, $updateQuery)) {
            // Optional: Log this rejection action if needed
            echo "<script>alert('Card application rejected successfully.'); window.location.href = 'admin_card_app.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to reject card application.'); window.location.href = 'admin_card_app.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve/Reject Card Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">HORIZON BANK</div>
        <div class="menu">
            <a href="dashboard.php">Home</a>
            <a href="req_account.php">New Accounts Request</a>
            <a href="reg_acc.php">Registered Accounts</a>
            <a href="feedback.php">Feedback</a>
            <a href="admin_loan_app.php">Loan Section</a>
            <a href="admin_invest_app.php">Investment Section</a>
            <a href="admin_card_app.php">Card Section</a>
        </div>
        <div class="right">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> </span>
            <form method="POST" action="admin_logout.php">
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </div>

    <div>
        <h2 class="text-center">Pending Card Applications</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Card Type</th>
                    <th>Requested Limit (₹)</th>
                    <th>Employment Status</th>
                    <th>Annual Income</th>
                    <th>Credit Score</th>
                    <th>Income Proof</th>
                    <th>Identity Proof</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['firstName']} {$row['lastName']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['cardType']}</td>
                                <td>{$row['cardLimit']}</td>
                                <td>{$row['employmentStatus']}</td>
                                <td>{$row['annualIncome']}</td>
                                <td>{$row['creditScore']}</td>
                                <td><a href='../uimg/{$row['incomeProof']}' target='_blank'>View Income Proof</a></td>
                                <td><a href='../uimg/{$row['identityProof']}' target='_blank'>View Identity Proof</a></td>
                                <td>" . ucfirst($row['status']) . "</td>
                                <td>
                                    <a href='admin_card_app.php?action=approve&id={$row['id']}' class='btn btn-success'>Approve</a>
                                    <a href='admin_card_app.php?action=reject&id={$row['id']}' class='btn btn-danger'>Reject</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>No pending applications.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
