<?php
ob_start();

session_start(); // Assuming the user is logged in and has a session
include('includes/header.html');
include('includes/connect.php');

if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

$firstName = $_SESSION['nm'];
$lastName = $_SESSION['lastName'];
$balance=$_SESSION['balance'];
$userName = $_SESSION['user_nm'];
$email = $_SESSION['email'];
$phoneNumber = $_SESSION['mob'];

// Get the user ID from the session
$user_id = $_SESSION['id']; // Assuming the user ID is stored in the session

// Fetch the feedback from the database, including the admin's reply (if available)
$feedback_sql = "SELECT feedback.id, feedback.feedback_text, feedback.admin_reply, feedback.status 
                 FROM feedback 
                 WHERE feedback.user_id = ? ORDER BY feedback.id DESC";
$stmt = $conn->prepare($feedback_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$feedback_result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form input
    $feedback_text = htmlspecialchars($_POST['feedback_text']);

    // Prepare SQL query to insert data into feedback table
    $sql = "INSERT INTO feedback (user_id, feedback_text) VALUES ('$user_id', '$feedback_text')";

    if ($conn->query($sql) === TRUE) {
        echo "Feedback submitted successfully!";
        echo "<script>window.location.href = 'u_dashboard.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/reg1.css">
     <!-- Font Awesome for Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Submit Feedback</title>
</head>

<body>
<?php include('includes/navbar.php');?>



    <label style="font-size: 20px;">Submit Your Feedback or Complaint : </label>

    <!-- Feedback Submission Form -->
    <form method="POST" action="feedback.php">
        <label for="feedback_text">Feedback / Complaint:</label><br>
        <textarea id="feedback_text" name="feedback_text" rows="5" cols="40" required></textarea><br><br>

        <button type="submit">Submit</button>
    </form>

    <hr>

    <!-- Display Feedback and Admin Reply -->
    <label style="font-size: 20px;">Your Feedback History</label>
    <?php if ($feedback_result->num_rows > 0): ?>
        <?php while ($row = $feedback_result->fetch_assoc()): ?>
            <div class="feedback-item">
                <p><strong>Feedback:</strong> <?php echo nl2br(htmlspecialchars($row['feedback_text'])); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>

                <?php if ($row['status'] == 'replied'): ?>
                    <p><strong>Admin's Reply:</strong> <?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></p>
                <?php else: ?>
                    <p><strong>Waiting for admin's reply...</strong></p>
                <?php endif; ?>
            </div>
            <hr>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: white;">You have not submitted any feedback yet.</p>
    <?php endif; ?>
</body>

</html>