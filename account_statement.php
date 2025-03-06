<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}
include('includes/connect.php');
include('includes/header.html');

$account_number = $_SESSION['acc_no'];
$balance = $_SESSION['balance'];
$email = $_SESSION['email'];
$name = $_SESSION['nm'];
$mobile_number = $_SESSION['mob'];
$user_id = $_SESSION['id'];
$profile_img = $_SESSION['photo'];

// Fetch user's transaction history (both sent and received)
$transaction_sql = "
    SELECT t.id, t.sender_acc_no, t.receiver_acc_no, t.amount, t.transaction_date,
           u.firstName AS receiver_name
    FROM transactions t
    LEFT JOIN accounts a_sender ON t.sender_acc_no = a_sender.acc_no
    LEFT JOIN accounts a_receiver ON t.receiver_acc_no = a_receiver.acc_no
    LEFT JOIN users u ON u.id = a_receiver.id
    WHERE t.sender_id = ? OR t.reciver_id = ?  -- Filter by the sender_id or reciver_id
    ORDER BY t.transaction_date DESC
";

$transaction_stmt = $conn->prepare($transaction_sql);

// Assuming $user_id is the ID of the user (i.e., sender or receiver)
$transaction_stmt->bind_param("ii", $user_id, $user_id);  // Binding the user_id twice for sender_id and reciver_id

$transaction_stmt->execute();
$transaction_stmt->store_result();
$transaction_stmt->bind_result($transaction_id, $sender_acc_no, $receiver_acc_no, $amount, $transaction_date, $receiver_name);

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

    <!-- Navbar -->
<div class="navbar">
        <div class="logo">
            <img src="bank_img/logo_fade.jpeg" alt="Bank Logo">
            <span>HORIZON BANK</span>
        </div>
        <div class="menu">
            <a href="u_dashboard.php"><i class="fas fa-home"></i> Home</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="account_statement.php"><i class="fas fa-file-alt"></i> Account Statement</a>
            <a href="transaction.php"><i class="fas fa-exchange-alt"></i> Fund Transfer</a>
            <a href="feedback.php"><i class="fas fa-comments"></i> Feedback/Complaints</a>
            <a href="loan.php"><i class="fas fa-hand-holding-usd"></i> Loan</a>
            <a href="invest.php"><i class="fas fa-chart-line"></i> Investment</a>
        </div>
        <div class="right">
            <span class="balance"><i class="fas fa-wallet"></i> Balance: ₹<?php echo number_format($balance, 2); ?></span>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Account Statement Section -->
    <div id="account_statement">
        <h2>Transaction History</h2>

        <?php if ($transaction_stmt->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Sender Account NO.</th>
                        <th>Receiver Account NO.</th>
                        <th>Amount (₹)</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction_stmt->fetch()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction_id); ?></td>
                            <td><?php echo htmlspecialchars($sender_acc_no); ?></td>
                            <td><?php echo htmlspecialchars($receiver_acc_no); ?></td>
                            <td><?php echo number_format($amount, 2); ?></td>
                            <td><?php echo htmlspecialchars($transaction_date); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transactions found.</p>
        <?php endif; ?>

    </div>
    <?php
    $transaction_stmt->close();
    $conn->close();
    ?>
</body>

</html>