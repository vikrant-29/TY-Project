<?php
ob_start();

session_set_cookie_params(1800);  // Set the cookie expiration time to 30 minutes (1800 seconds)
ini_set('session.gc_maxlifetime', 1800); // Set the session max lifetime to 30 minutes
session_start();


include('../includes/header.html');
include('../includes/connect.php');  // Update this path if needed

if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all loan applications, regardless of status
$query = "SELECT * FROM loan_applications";
$result = mysqli_query($conn, $query);

// Handle approve or reject action
if (isset($_POST['approve']) || isset($_POST['reject'])) {
    $loanId = $_POST['loan_id'];
    $action = isset($_POST['approve']) ? 'Approved' : 'Rejected';

    // Update approval status
    $updateQuery = "UPDATE loan_applications SET approval_status = '$action' WHERE id = '$loanId'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Loan application $action successfully.'); window.location.href = 'admin_loan_app.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to $action loan application.'); window.location.href = 'admin_loan_app.php';</script>";
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve/Reject Loan Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>

<body>
    <!-- Navbar -->
    <?php include('../includes/admin_navbar.php');?>

    
        <h2 class="text-center">Loan Applications</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Loan Type</th>
                    <th>Loan Amount</th>
                    <th>Purpose</th>
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
                                <td>{$row['loanType']}</td>
                                <td>{$row['loanAmount']}</td>
                                <td>{$row['loanPurpose']}</td>
                                <td>{$row['employmentStatus']}</td>
                                <td>{$row['annualIncome']}</td>
                                <td>{$row['creditScore']}</td>
                                <td><a href='../uimg/{$row['incomeProof']}' target='_blank'>View Income Proof</a></td>
                                <td><a href='../uimg/{$row['identityProof']}' target='_blank'>View Identity Proof</a></td>
                                <td>{$row['approval_status']}</td>
                                <td>
                                    <form action='admin_loan_app.php' method='POST'>
                                        <input type='hidden' name='loan_id' value='{$row['id']}'>
                                        " . ($row['approval_status'] == 'Pending' ? "
                                        <button type='submit' name='approve' class='btn btn-success'>Approve</button>
                                        <button type='submit' name='reject' class='btn btn-danger'>Reject</button>" : "
                                        <span class='badge badge-secondary'>Already Processed</span>") . "
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>No loan applications available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
