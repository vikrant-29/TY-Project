<?php
ob_start();

session_start();
include('../includes/header.html');
include('../includes/connect.php');

if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all users who have been assigned an account number
$registered_sql = "SELECT users.id, users.firstName, users.lastName, users.userName, users.email, users.phoneNumber, users.aadharNo, accounts.acc_no, accounts.balance
                   FROM users 
                   INNER JOIN accounts ON users.id = accounts.id
                   WHERE accounts.acc_no IS NOT NULL";
$registered_users_result = $conn->query($registered_sql);

if (!$registered_users_result) {
    die("Error executing query: " . $conn->error);
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $balance = $_POST['balance'];

    // Update user details
    $update_sql = "UPDATE users SET firstName = ?, lastName = ?, userName = ?, email = ?, phoneNumber = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('sssssi', $first_name, $last_name, $username, $email, $phone_number, $user_id);

    if ($stmt->execute()) {
        // If user details update is successful, update the balance in the accounts table
        $update_balance_sql = "UPDATE accounts SET balance = ? WHERE id = ?";
        $balance_stmt = $conn->prepare($update_balance_sql);
        $balance_stmt->bind_param('di', $balance, $user_id); // 'd' for decimal, 'i' for integer

        if ($balance_stmt->execute()) {
            $_SESSION['message'] = 'User information and balance updated successfully!';
            header('Location: reg_acc.php');  // Redirect to this page to show the message
        } else {
            $_SESSION['message'] = "Error updating balance: " . $balance_stmt->error;
            header('Location: reg_acc.php');  // Redirect to show the error message
        }
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        header('Location: reg_acc.php');  // Redirect to show the error message
    }

    exit();
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Delete user from the database
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'User deleted successfully!';
        header('Location: reg_acc.php');  // Redirect to this page to show the message
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        header('Location: reg_acc.php');  // Redirect to show the error message
    }

    exit();
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

    <!-- Display session messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <script>
            alert('<?php echo htmlspecialchars($_SESSION['message']); ?>');
        </script>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
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

    <div id="reg_acc">
        <h3>Users with Assigned Account Numbers</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Aadhar Number</th>
                    <th>Phone Number</th>
                    <th>Balance</th>
                    <th>Account Number</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($registered_users_result->num_rows > 0): ?>
                    <?php while ($row = $registered_users_result->fetch_assoc()): ?>
                        <form method="POST" action="reg_acc.php">
                            <tr>
                                <td><input type="text" name="first_name" value="<?php echo htmlspecialchars($row['firstName']); ?>" required></td>
                                <td><input type="text" name="last_name" value="<?php echo htmlspecialchars($row['lastName']); ?>" required></td>
                                <td><input type="text" name="username" value="<?php echo htmlspecialchars($row['userName']); ?>" required></td>
                                <td><input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required></td>
                                <td><input type="text" name="aadhar_number" value="<?php echo htmlspecialchars($row['aadharNo']); ?>" required></td>
                                <td><input type="text" name="phone_number" value="<?php echo htmlspecialchars($row['phoneNumber']); ?>" required></td>
                                <td><input type="number" step="0.01" name="balance" value="<?php echo htmlspecialchars($row['balance']); ?>" required></td>
                                <td><?php echo htmlspecialchars($row['acc_no']); ?></td>
                                <td>
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="update_user">Update</button>
                                </td>
                                <td>
                                    <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </td>
                            </tr>
                        </form>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No registered users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>