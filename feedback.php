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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $feedback_text = $_POST['feedback_text'];

    // Validate the feedback input
    if (empty($feedback_text)) {
        $error_message = "Feedback cannot be empty.";
    } else {
        // Insert feedback into the database
        $insert_sql = "INSERT INTO feedback (user_id, feedback_text) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("is", $user_id, $feedback_text);
        if ($stmt->execute()) {
            $success_message = "Your feedback has been submitted successfully.";
        } else {
            $error_message = "Error submitting feedback. Please try again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Feedback Form Section -->
    <div class="feedback-container">
        <h2 style="position: relative; text-align:center;">Submit Your Feedback</h2>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <form action="feedback.php" method="POST">
            <textarea name="feedback_text" rows="6" cols="50" placeholder="Write your feedback here..." required></textarea><br><br>
            <button type="submit" name="submit_feedback">Submit Feedback</button>
        </form>
        <p style="position: relative; text-align:center;">Finish submitting ?<a href="dashboard.php">Go back</a></p>
    </div>

</body>
</html>
