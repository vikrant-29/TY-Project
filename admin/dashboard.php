<?php
ob_start();

session_start();
include('../includes/header.html');
include('../includes/connect.php');
//Redirect to login if admin is not logged in
if (!isset($_SESSION['ad_login'])) {
    header('Location: admin_login.php');
    exit();
}

if($_SESSION['id']==13324)
{
    $path = "../bank_img/adi.jpg";
}
else
{
    $path = "../bank_img/adm2.jpg";
}

// Fetch feedback data along with user details
$feedback_sql = "SELECT feedback.id, feedback.feedback_text, users.firstName, users.email, users.phoneNumber 
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


    <h2 style="text-align: center;">Admin Panel</h2>
    <!-- Home Section -->
    <div id="home">
    <div id="profile">
            <div class="profile-card">
                <img src="<?php echo $path;?>" alt="Profile Background" class="profile-bg">
                <div class="profile-content">
                    <p><strong>ID:</strong><?php echo htmlspecialchars($_SESSION['id']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['unm']); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
                    <p><strong>Name:</strong><?php echo htmlspecialchars($_SESSION['name']); ?></p>
                    
                </div>
            </div>
        </div>
    </div>    
</body>

</html>