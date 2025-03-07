<?php
ob_start();

session_start();
include('../includes/header.html');
include('../includes/connect.php');  // Update this path if needed

// Check if the admin is logged in
if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all card applications, regardless of status
$query = "SELECT * FROM card_applications";
$result = mysqli_query($conn, $query);

// Handle approve or reject action
if (isset($_POST['approve']) || isset($_POST['reject'])) {
    $cardId = $_POST['card_id'];
    $action = isset($_POST['approve']) ? 'approved' : 'rejected';

    // Update approval status
    $updateQuery = "UPDATE card_applications SET status = '$action' WHERE id = '$cardId'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Card application $action successfully.'); window.location.href = 'admin_card_app.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to $action card application.'); window.location.href = 'admin_card_app.php';</script>";
    }
}

ob_end_flush();
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
    <?php include('../includes/admin_navbar.php'); ?>

    <div>
        <h2>Card Applications</h2>
        <table border="1">
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
                        // Highlight pending applications with a yellow background
                        //$statusClass = ($row['status'] == 'pending') ? 'table-warning' : '';
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
                                    <form action='admin_card_app.php' method='POST'>
                                        <input type='hidden' name='card_id' value='{$row['id']}'>
                                        " . ($row['status'] == 'pending' ? "
                                        <button type='submit' name='approve' class='btn btn-success'>Approve</button>
                                        <button type='submit' name='reject' class='btn btn-danger'>Reject</button>" : "
                                        <span class='badge badge-secondary'>Already Processed</span>") . "
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>No card applications available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
