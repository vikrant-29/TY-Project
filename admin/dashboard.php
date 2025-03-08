<?php
ob_start();


session_set_cookie_params(1800);  // Set session cookie timeout to 30 minutes (1800 seconds)
ini_set('session.gc_maxlifetime', 1800); // Set session lifetime to 30 minutes
session_start(); // Start the session

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
<?php include('../includes/admin_navbar.php');?>


    <h2 style="text-align: center;">Admin Panel</h2>
    <!-- Home Section -->
    <div id="home">
    <div id="profile">
    <div class="profile-card">
        <img src="<?php echo $path;?>" alt="Profile Background" class="profile-bg">
        <div class="profile-content">
            <p><i class="fas fa-id-badge"></i> <strong>ID:</strong> <?php echo htmlspecialchars($_SESSION['id']); ?></p>
            <p><i class="fas fa-user"></i> <strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['unm']); ?></p>
            <p><i class="fas fa-briefcase"></i> <strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            <p><i class="fas fa-signature"></i> <strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        </div>
    </div>
</div>

</body>

</html>