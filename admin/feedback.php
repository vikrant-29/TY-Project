<?php
ob_start();

session_start();
include('../includes/header.html');
include('../includes/connect.php');

if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle the admin reply submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback_id'])) {
    $feedback_id = $_POST['feedback_id'];
    
    $admin_reply = htmlspecialchars($_POST['admin_reply']);

    // Update the feedback with the admin reply and change status to 'replied'
    $update_sql = "UPDATE feedback SET admin_reply = ?, status = 'replied' WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $admin_reply, $feedback_id);

    if ($stmt->execute()) {
        echo "Reply submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch feedback data along with user details
$feedback_sql = "SELECT feedback.id, feedback.feedback_text, feedback.admin_reply, feedback.status, 
                        users.firstName, users.email, users.phoneNumber 
                 FROM feedback 
                 INNER JOIN users ON feedback.user_id = users.id";
$feedback_result = $conn->query($feedback_sql);

if (!$feedback_result) {
    die("Error executing query: " . $conn->error);
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Bank Management</title>
    <link rel="stylesheet" href="../style/admin.css"> <!-- Link to the external CSS file -->
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
        </div>
        <div class="right">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> </span>
            <form method="POST" action="admin_logout.php">
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- Feedback Section -->
    <div id="feedback">
        <h3>Users' Feedback</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Feedback</th>
                    <th>Admin Reply</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($feedback_result->num_rows > 0): ?>
                    <?php while ($row = $feedback_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phoneNumber']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['feedback_text'])); ?></td>
                            <td>
                                <?php if ($row['status'] == 'replied'): ?>
                                    <p><strong>Reply:</strong> <?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></p>
                                <?php else: ?>
                                    <form method="POST" action="feedback.php">
                                        <textarea name="admin_reply" rows="5" cols="40" placeholder="Write your reply here..."></textarea><br><br>
                                        <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit">Send Reply</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <p>Status: Pending</p>
                                <?php else: ?>
                                    <p>Status: Replied</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No feedback submitted yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
