<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bank_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch user account information (account number, balance, email, mobile number, etc.)
$sql = "SELECT account_number, balance, name, email, phone_number FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($account_number, $balance, $name, $email, $mobile_number);
$stmt->fetch();
$stmt->close();

// Check if user has account number
if ($account_number == NULL) {
    $account_pending = true;
} else {
    $account_pending = false;
}

// Fetch user's transaction history (both sent and received)
$transaction_sql = "
    SELECT t.id, t.sender_id, t.receiver_id, t.amount, t.transaction_date, u.name AS receiver_name 
    FROM transactions t
    LEFT JOIN users u ON u.id = t.receiver_id
    WHERE t.sender_id = ? OR t.receiver_id = ?
    ORDER BY t.transaction_date DESC
";
$transaction_stmt = $conn->prepare($transaction_sql);
$transaction_stmt->bind_param("ii", $user_id, $user_id);
$transaction_stmt->execute();
$transaction_stmt->store_result();
$transaction_stmt->bind_result($transaction_id, $sender_id, $receiver_id, $amount, $transaction_date, $receiver_name);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transfer'])) {
    $recipient_account_number = $_POST['recipient_account_number'];
    $transfer_amount = $_POST['amount'];

    // Check if transfer amount is valid
    if ($transfer_amount <= 0) {
        $error_message = "Amount must be greater than 0.";
    } elseif ($balance < $transfer_amount) {
        $error_message = "Insufficient balance.";
    } else {
        // Check if recipient account exists
        $recipient_sql = "SELECT id, balance FROM users WHERE account_number = ?";
        $recipient_stmt = $conn->prepare($recipient_sql);
        $recipient_stmt->bind_param("s", $recipient_account_number);
        $recipient_stmt->execute();
        $recipient_stmt->store_result();

        if ($recipient_stmt->num_rows == 0) {
            $error_message = "Recipient account not found.";
        } else {
            $recipient_stmt->bind_result($recipient_id, $recipient_balance);
            $recipient_stmt->fetch();

            // Begin a transaction to ensure data consistency
            $conn->begin_transaction();

            try {
                // Update sender's balance
                $new_sender_balance = $balance - $transfer_amount;
                $update_sender_sql = "UPDATE users SET balance = ? WHERE id = ?";
                $update_sender_stmt = $conn->prepare($update_sender_sql);
                $update_sender_stmt->bind_param("di", $new_sender_balance, $user_id);
                $update_sender_stmt->execute();

                // Update receiver's balance
                $new_receiver_balance = $recipient_balance + $transfer_amount;
                $update_receiver_sql = "UPDATE users SET balance = ? WHERE id = ?";
                $update_receiver_stmt = $conn->prepare($update_receiver_sql);
                $update_receiver_stmt->bind_param("di", $new_receiver_balance, $recipient_id);
                $update_receiver_stmt->execute();

                // Record the transaction in the transactions table
                $transaction_sql = "INSERT INTO transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)";
                $transaction_stmt = $conn->prepare($transaction_sql);
                $transaction_stmt->bind_param("iii", $user_id, $recipient_id, $transfer_amount);
                $transaction_stmt->execute();

                // Commit the transaction
                $conn->commit();

                // Update balance after successful transaction
                $balance = $new_sender_balance;

                // Success message
                $success_message = "Transfer successful! ₹" . number_format($transfer_amount, 2) . " has been sent to account number " . htmlspecialchars($recipient_account_number) . ".";
            } catch (Exception $e) {
                // Rollback transaction in case of an error
                $conn->rollback();
                $error_message = "An error occurred. Please try again.";
            }
        }
        $recipient_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Bank Management</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="bank_logo.png" alt="Bank Logo">
        </div>
        <div class="menu">
            <a href="#home">Home</a>
            <a href="#profile">Profile</a>
            <a href="#account_statement">Account Statement</a>
            <a href="#fund_transfer">Fund Transfer</a>
        </div>
        <div class="right">
            <span class="balance">Balance: ₹<?php echo number_format($balance, 2); ?></span>
            <a href="login.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- User Dashboard Content -->
    <div id="home">
        <div class="content">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

            <?php if ($account_pending): ?>
                <p>Your account is pending approval. Please wait for admin approval before you can perform any transactions.</p>
            <?php else: ?>
                <p>Your account number is: <?php echo htmlspecialchars($account_number); ?></p>
                <p><a href="#account_statement">Go to Transactions</a></p>
                <!-- Four Card Containers -->
                <div class="cards-container">
                    <div class="card">
                        <img src="img\notification.jpg" alt="Notifications" class="card-image">
                        <h3>Latest Notifications</h3>
                        <p>Check the latest updates and notices.</p>
                        <a href="#">View Notices</a>
                    </div>
                    <div class="card">
                        <img src="img/contact.jpg" alt="Contact Us" class="card-image">
                        <h3>Feedback</h3>
                        <p>If you have any queries or something to say, feel free to reach out to us.</p>
                        <a href="feedback.php">Submit Feedback</a>
                    </div>

                    <div class="card">
                        <img src="img/summary.jpg" alt="Account Summary" class="card-image">
                        <h3>Account Summary</h3>
                        <p>View details of your account and recent activities.</p>
                        <a href="#account_statement">View Summary</a>
                    </div>
                    <div class="card">
                        <img src="img/transfer.jpg" alt="Transfer Money" class="card-image">
                        <h3>Transfer Money</h3>
                        <p>Send funds to another account.</p>
                        <a href="#fund_transfer">Transfer Now</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Profile Section with User Information -->
        <div id="profile">
            <div class="profile-card">
                <img src="img\profile.jpg" alt="Profile Background" class="profile-bg">
                <div class="profile-content">
                    <h3><?php echo htmlspecialchars($name); ?></h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Mobile:</strong> <?php echo htmlspecialchars($mobile_number); ?></p>
                    <p><strong>Balance:</strong> ₹<?php echo number_format($balance, 2); ?></p>
                </div>
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
                            <th>Sender ID</th>
                            <th>Receiver</th>
                            <th>Amount (₹)</th>
                            <th>Transaction Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction_stmt->fetch()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction_id); ?></td>
                                <td><?php echo htmlspecialchars($sender_id); ?></td>
                                <td><?php echo htmlspecialchars($receiver_name); ?></td>
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
        <!-- Fund Transfer Section -->
        <div id="fund_transfer">
            <?php if ($account_pending): ?>
                <p>Your account is pending approval. You cannot transfer money until it is approved.</p>
            <?php else: ?>
                <h2>Transfer Money</h2>

                <?php if (isset($error_message)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
                <?php endif; ?>

                <?php if ($balance > 0): ?>
                    <form action="#" method="POST">
                        <label for="recipient_account_number">Recipient Account Number:</label><br>
                        <input type="text" id="recipient_account_number" name="recipient_account_number" required><br><br>

                        <label for="amount">Amount (₹):</label><br>
                        <input type="number" id="amount" name="amount" step="0.01" min="0.01" required><br><br>

                        <button type="submit" name="transfer">Transfer</button>
                    </form>
                <?php else: ?>
                    <p>Your balance is ₹0. You cannot transfer money.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>

<?php
$transaction_stmt->close();
$conn->close();
?>