<?php
// admin_panel.php

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect to admin login if not logged in
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bank_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users who have not been assigned an account number (considered 'pending')
$sql = "SELECT * FROM users WHERE account_number IS NULL";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . $conn->error);
}

function generateAccountNumber($conn) {
    $account_number = rand(1000000000, 9999999999); // Generate a random 10-digit number
    // Ensure the account number is unique
    while (true) {
        $check_sql = "SELECT id FROM users WHERE account_number = '$account_number'";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows == 0) {
            break; // If account number is unique, exit the loop
        }
        $account_number = rand(1000000000, 9999999999); // Generate a new account number if not unique
    }
    return $account_number;
}

// Fetch all users who have been assigned an account number (i.e., those not "pending")
$registered_sql = "SELECT * FROM users WHERE account_number IS NOT NULL";
$registered_users_result = $conn->query($registered_sql);

// Check if the query was successful
if (!$registered_users_result) {
    die("Error executing query: " . $conn->error);
}

// Fetch feedback data along with user details
$feedback_sql = "SELECT feedback.id, feedback.feedback_text, users.name, users.email, users.phone_number 
FROM feedback 
INNER JOIN users ON feedback.user_id = users.id";
$feedback_result = $conn->query($feedback_sql);

// Check if the query was successful
if (!$feedback_result) {
    die("Error executing query: " . $conn->error);
}

// Handle account number assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_account'])) {
    $user_id = $_POST['user_id'];
    $account_number = generateAccountNumber($conn); // Generate unique account number

    // Update the user's account number
    $update_sql = "UPDATE users SET account_number = '$account_number' WHERE id = $user_id";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "Account number assigned successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Update user details
    $update_sql = "UPDATE users SET name = '$name', username = '$username', email = '$email', phone_number = '$phone_number' WHERE id = $user_id";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['message'] = "User information updated successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    // Redirect back to the admin panel
    header('Location: admin_panel.php');
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Delete user from the database
    $delete_sql = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    // Redirect back to the admin panel
    header('Location: admin_panel.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Bank Management</title>
    <link rel="stylesheet" href="admin.css"> <!-- Link to the external CSS file -->
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">BankLogo</div>
        <div class="menu">
            <a href="#home">Home</a>
            <a href="#req_account">New Accounts Request</a>
            <a href="#reg_acc">Registered Accounts</a>
            <a href="#feedback">Feedback</a>
        </div>
        <div class="right">
            <span>Welcome, Admin</span>
            <form method="POST" action="logout.php">
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </div>

    <h2>Admin Panel</h2>
    <!-- Home Section -->
    <div id="home">
        <h3>Admin 1</h3>
        <div class="card">
        <img src="img/img3.jpg" alt="admin 1">
        </div>
        <h3>Admin 2</h3>
        <div class="card">

        <img src="img/adi2.jpg" alt="admin 2">
        </div>
    </div>
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
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()): ?>
                        <form method="POST" action="admin_panel.php">
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['aadhar_number']; ?></td>
                                <td><?php echo $row['phone_number']; ?></td>

                                <!-- Generate and show the suggested 10-digit account number -->
                                <td>
                                    <?php
                                    $suggested_account_number = generateAccountNumber($conn);
                                    echo $suggested_account_number;
                                    ?>
                                </td>

                                <!-- If the account number is not assigned, show the form to assign it -->
                                <td>
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="assign_account">Assign</button>
                                </td>
                            </tr>
                        </form>
                <?php endwhile;
                } else {
                    echo "<tr><td colspan='7'>No pending users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<!-- Registered Accounts Section -->
<div id="reg_acc">
        <h3>Users with Assigned Account Numbers</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Aadhar Number</th>
                    <th>Phone Number</th>
                    <th>Account Number</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($registered_users_result->num_rows > 0) {
                    while ($row = $registered_users_result->fetch_assoc()): ?>
                        <form method="POST" action="admin_panel.php">
                            <tr>
                                <td><input type="text" name="name" value="<?php echo $row['name']; ?>" required></td>
                                <td><input type="text" name="username" value="<?php echo $row['username']; ?>" required></td>
                                <td><input type="email" name="email" value="<?php echo $row['email']; ?>" required></td>
                                <td><input type="text" name="aadhar_number" value="<?php echo $row['aadhar_number']; ?>" required></td>
                                <td><input type="text" name="phone_number" value="<?php echo $row['phone_number']; ?>" required></td>
                                <td><?php echo $row['account_number']; ?></td>
                                <td><button type="submit" name="update_user">Update</button></td>
                                <td>
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </td>
                            </tr>
                        </form>
                <?php endwhile;
                } else {
                    echo "<tr><td colspan='8'>No registered users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="feedback">
    <h3>Users' Feedback</h3>
    <table border="1">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($feedback_result->num_rows > 0) {
                while ($row = $feedback_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['feedback_text']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No feedback submitted yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>

</html>
