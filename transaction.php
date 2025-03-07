<?php
ob_start();

include('includes/header.html');
include('includes/connect.php');
session_start();

if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

$status = $_SESSION['ac_status'];
$balance = $_SESSION['balance'];
$account_number = $_SESSION['acc_no'];
$user_id = $_SESSION['id']; // Assuming user_id is stored in the session


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
        $recipient_sql = "
        SELECT a.acc_no, a.balance, u.id AS reciver_id 
        FROM accounts a
        LEFT JOIN users u ON a.id = u.id  -- Assuming there is a `user_id` in `accounts` table that references `id` in `users`
        WHERE a.acc_no = ?
        ";
    
        $recipient_stmt = $conn->prepare($recipient_sql);
        $recipient_stmt->bind_param("i", $recipient_account_number);
        $recipient_stmt->execute();
        $recipient_stmt->store_result();
    
        // Bind the result once, then fetch it
        $recipient_stmt->bind_result($recipient_account_no, $recipient_balance, $reciver_id);
    
        if ($recipient_stmt->num_rows == 0) {
            $error_message = "Recipient account not found.";
        } else {
            // Fetch the recipient data
            $recipient_stmt->fetch();
    
            // Begin a transaction to ensure data consistency
            $conn->begin_transaction();
    
            try {
                // Update sender's balance
                $new_sender_balance = $balance - $transfer_amount;
                $update_sender_sql = "UPDATE accounts SET balance = ? WHERE acc_no = ?";
                $update_sender_stmt = $conn->prepare($update_sender_sql);
                $update_sender_stmt->bind_param("di", $new_sender_balance, $account_number); // Using acc_no for sender
                $update_sender_stmt->execute();
    
                // Update receiver's balance
                $new_receiver_balance = $recipient_balance + $transfer_amount;
                $update_receiver_sql = "UPDATE accounts SET balance = ? WHERE acc_no = ?";
                $update_receiver_stmt = $conn->prepare($update_receiver_sql);
                $update_receiver_stmt->bind_param("di", $new_receiver_balance, $recipient_account_number);
                $update_receiver_stmt->execute();
    
                // Record the transaction in the transactions table
                // Now, we include the user_id when inserting the transaction record
                $transaction_sql = "INSERT INTO transactions (sender_acc_no, receiver_acc_no, amount, sender_id, reciver_id) 
                                    VALUES (?, ?, ?, ?, ?)";
                $transaction_stmt = $conn->prepare($transaction_sql);
                $transaction_stmt->bind_param("iiiii", $account_number, $recipient_account_number, $transfer_amount, $user_id, $reciver_id);
                $transaction_stmt->execute();
    
                // Commit the transaction
                $conn->commit();
    
                // Update balance after successful transaction
                $balance = $new_sender_balance;
    
                // Success message
                $success_message = "Transfer successful! ₹" . number_format($transfer_amount, 2) . " has been sent to account number " . htmlspecialchars($recipient_account_number) . ".";
                echo "<script> alert('$success_message');window.location.href = 'u_dashboard.php';</script>";
            } catch (Exception $e) {
                // Rollback transaction in case of an error
                echo $e;
                $conn->rollback();
                $error_message = "An error occurred. Please try again.";
                echo "<script> alert('$error_message')</script>";
                header('location : transaction.php');
            }
        }
    
        $recipient_stmt->close();
    }
    
}

ob_end_flush();

?>
<html>
<link rel="stylesheet" href="style/u_dashboard.css">
 <!-- Font Awesome for Icons -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 <?php include('includes/navbar.php');?>


<div id="fund_transfer">
    <?php if ($status): ?>
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
                <input type="number" id="amount" name="amount" required><br><br>

                <button type="submit" name="transfer">Transfer</button>
            </form>
        <?php else: ?>
            <p>Your balance is ₹0. You cannot transfer money.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</html>
