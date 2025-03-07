<?php
session_start();
include('includes/header.html');
include('includes/connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['id']; 

// Fetch loan application details for the user with the approval status
$query = "SELECT * FROM loan_applications WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$application = mysqli_fetch_assoc($result);

$status = $application ? $application['approval_status'] : 'No application found';

// Fetch additional loan details if the application is approved
if ($status == 'Approved') {
    $loanType = $application['loanType'];
    $loanAmount = $application['loanAmount'];
    $loanPurpose = $application['loanPurpose'];
    $employmentStatus = $application['employmentStatus'];
    $annualIncome = $application['annualIncome'];
    $creditScore = $application['creditScore'];
    $incomeProof = $application['incomeProof'];
    $identityProof = $application['identityProof'];
    $submissionDate = $application['submissionDate'];
    $approvalDate = $application['approval_status'] == 'Approved' ? $application['submissionDate'] : 'Not available';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Status</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/u_dashboard.css">
    <!-- Custom CSS -->
    <style>
    .contain {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        margin-top: 30px;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 217, 255, 0.5); /* Neon blue shadow */
        backdrop-filter: blur(10px);
        text-align: center;
    }

    .h {
        color: #00d9ff; /* Neon blue color */
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0, 217, 255, 0.5); /* Neon glow effect */
        margin-bottom: 20px;
    }

    p {
        font-size: 18px;
        color: #d1f0ff; /* Light blue text */
    }

    p strong {
        color: #00d9ff; /* Neon blue for strong text */
    }

    .btn-primary {
        background-color: #00d9ff; /* Neon blue color */
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        color: black; /* Dark text for contrast */
        font-weight: bold;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: #ff0077; /* Neon pink on hover */
        box-shadow: 0 0 15px rgba(255, 0, 119, 0.8); /* Neon pink glow */
        color: white; /* White text on hover */
    }

    .fas {
        margin-right: 10px; /* Add spacing between icon and text */
    }

    .loan-details {
        background: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
        border-radius: 10px;
        margin-top: 20px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 217, 255, 0.5);
    }

    .loan-details p {
        font-size: 16px;
        color: #d1f0ff;
    }
    </style>
</head>
<body>

<?php include('includes/navbar.php');?>


    <div class="contain">
        <h2 class="h"><i class="fas fa-file-invoice"></i> Your Loan Application Status</h2>
        <p>Your current application status is: <strong><?php echo $status; ?></strong></p>
        
        <?php if ($status == 'Approved'): ?>
            <div class="loan-details">
                <h4>Approved Loan Details</h4>
                <p><strong>Full Name:</strong> <?php echo $application['firstName'] . ' ' . $application['lastName']; ?></p>
                <p><strong>Loan Type:</strong> <?php echo $loanType; ?></p>
                <p><strong>Loan Amount:</strong> ₹<?php echo number_format($loanAmount, 2); ?></p>
                <p><strong>Loan Purpose:</strong> <?php echo $loanPurpose; ?></p>
                <p><strong>Employment Status:</strong> <?php echo $employmentStatus; ?></p>
                <p><strong>Annual Income:</strong> ₹<?php echo number_format($annualIncome, 2); ?></p>
                <p><strong>Credit Score:</strong> <?php echo $creditScore; ?></p>
                <p><strong>Income Proof:</strong> <a href="uimg/<?php echo $incomeProof; ?>" target="_blank">View Income Proof</a></p>
                <p><strong>Identity Proof:</strong> <a href="uimg/<?php echo $identityProof; ?>" target="_blank">View Identity Proof</a></p>
                <p><strong>Application Submission Date:</strong> <?php echo $submissionDate; ?></p>
                <p><strong>Approval Date:</strong> <?php echo $approvalDate; ?></p>
            </div>
        <?php endif; ?>
        
        <a href="loan.php" class="btn btn-primary"><i class="fas fa-hand-holding-usd"></i> Apply for a new loan</a>
    </div>
</body>
</html>
