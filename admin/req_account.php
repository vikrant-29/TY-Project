<?php
session_start();
include('../includes/header.html');
include('../includes/connect.php');

if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}
// Declare global variable for account number
$account_number = 0;

function generateAccountNumber($conn)
{
    global $account_number;  // Access the global variable
    $unique = false;

    while (!$unique) {
        // Generate a 10-digit number
        $account_number = rand(100000000, 999999999);

        // Check if the account number already exists in the database
        $check_sql = "SELECT id FROM accounts WHERE acc_no = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param('i', $account_number);
        $stmt->execute();
        $stmt->store_result();

        // If the account number does not exist, break the loop
        if ($stmt->num_rows == 0) {
            $unique = true;
        }
    }

    return $account_number;
}

// Fetch all users who have not been assigned an account number
$sql = "SELECT users.id, users.firstName, users.lastName, users.userName, users.email, users.phoneNumber, users.aadharNo 
        FROM users 
        LEFT JOIN accounts ON users.id = accounts.id  -- Assuming accounts.user_id is the user reference in the accounts table
        WHERE accounts.acc_no IS NULL";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Generate a unique account number before displaying the form
generateAccountNumber($conn);

// Handle account number assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_account'])) {
    $user_id = $_POST['user_id'];

    // Update the account details in the accounts table (if already exists)
    $update_sql = "UPDATE accounts 
                   SET acc_no = ?, account_type = 'Savings', balance = 0.00, status = 'Active' 
                   WHERE id = ?"; // Update user_id, assuming user_id links users to accounts

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('si', $account_number, $user_id); // 'si' for string and integer

    // Execute the update query
    if ($stmt->execute()) {
        // Check if any rows were updated
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Account number assigned/updated successfully!');</script>";
        } else {
            echo "<script>alert('No account found for this user. Account number was not updated.');</script>";
        }
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    echo "<script>window.location.href = 'dashboard.php';</script>";
}
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

    <!-- Pending Accounts Section -->
    <div id="req_account">
        <h3>Users Pending Account Assignment</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Aadhar Number</th>
                    <th>Phone Number</th>
                    <th>Suggested Account Number</th>
                    <th>Assign Account Number</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <form method="POST" action="req_account.php">
                            <tr>
                                <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                                <td><?php echo htmlspecialchars($row['userName']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['aadharNo']); ?></td>
                                <td><?php echo htmlspecialchars($row['phoneNumber']); ?></td>
                                <td><?php echo $account_number; ?></td> <!-- Use global variable for account number -->
                                <td>
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>"> <!-- Use hidden field for user_id -->
                                    <button type="submit" name="assign_account">Assign</button>
                                </td>
                            </tr>
                        </form>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No pending users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
