<?php
ob_start();
include('includes/header.html');
session_start();

$account_number = $_SESSION['acc_no'];
$balance = $_SESSION['balance'];
$email = $_SESSION['email'];
$name = $_SESSION['nm'];
$mobile_number = $_SESSION['mob'];
$user_id = $_SESSION['id'];
$profile_img = $_SESSION['photo'];

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMI Calculator - Horizon Bank</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
      <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/u_dashboard.css">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/Bank_Management_System/bank_img/back3.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        color: #d1f0ff;
    }

    .calculator-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 217, 255, 0.5); /* Neon blue shadow */
        backdrop-filter: blur(10px);
        color: #d1f0ff; /* Light blue text */
    }

    .calculator-container h2 {
        color: #00d9ff; /* Neon blue color */
        margin-bottom: 20px;
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0, 217, 255, 0.5); /* Neon glow effect */
    }

    .calculator-container label {
        font-weight: bold;
        color: #00d9ff; /* Neon blue color */
    }

    .calculator-container input[type="number"],
    .calculator-container input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #00d9ff; /* Neon blue border */
        border-radius: 5px;
        font-size: 16px;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
        color: #d1f0ff; /* Light blue text */
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .calculator-container input[type="number"]:focus,
    .calculator-container input[type="text"]:focus {
        border-color: #ff0077; /* Neon pink on focus */
        box-shadow: 0 0 10px rgba(255, 0, 119, 0.5); /* Neon pink glow */
        outline: none;
    }

    .calculator-container .btn-primary {
        background-color: #00d9ff; /* Neon blue color */
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        color: black; /* Dark text for contrast */
        font-weight: bold;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .calculator-container .btn-primary:hover {
        background-color: #ff0077; /* Neon pink on hover */
        box-shadow: 0 0 15px rgba(255, 0, 119, 0.8); /* Neon pink glow */
        color: white; /* White text on hover */
    }

    .result {
        margin-top: 20px;
        padding: 15px;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
        border-radius: 10px;
        color: #d1f0ff; /* Light blue text */
        font-size: 18px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 217, 255, 0.3); /* Neon blue shadow */
    }

    .result span {
        color: #ff0077; /* Neon pink for the EMI value */
        font-weight: bold;
    }
</style>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="bank_img/logo_fade.jpeg" alt="Bank Logo">
            <span>HORIZON BANK</span>
        </div>
        <div class="menu">
            <a href="u_dashboard.php"><i class="fas fa-home"></i> Home</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="account_statement.php"><i class="fas fa-file-alt"></i> Account Statement</a>
            <a href="transaction.php"><i class="fas fa-exchange-alt"></i> Fund Transfer</a>
            <a href="feedback.php"><i class="fas fa-comments"></i> Feedback/Complaints</a>
            <a href="loan.php"><i class="fas fa-hand-holding-usd"></i> Loan</a>
            <a href="invest.php"><i class="fas fa-chart-line"></i> Investment</a>
        </div>
        <div class="right">
            <span class="balance"><i class="fas fa-wallet"></i> Balance: ₹<?php echo number_format($balance, 2); ?></span>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="calculator-container">
        <h2>EMI Calculator</h2>
        <form id="emiForm">
            <!-- Loan Amount -->
            <div class="form-group">
                <label for="loanAmount">Loan Amount (in ₹)</label>
                <input type="number" id="loanAmount" placeholder="Enter loan amount" required>
            </div>

            <!-- Interest Rate -->
            <div class="form-group">
                <label for="interestRate">Interest Rate (in %)</label>
                <input type="number" id="interestRate" placeholder="Enter interest rate" required>
            </div>

            <!-- Loan Tenure -->
            <div class="form-group">
                <label for="loanTenure">Loan Tenure (in months)</label>
                <input type="number" id="loanTenure" placeholder="Enter loan tenure" required>
            </div>

            <!-- Calculate Button -->
            <div class="form-group">
                <button type="button" class="btn btn-primary" onclick="calculateEMI()">Calculate EMI</button>
            </div>
        </form>

        <!-- Result Display -->
        <div class="result" id="result">
            Your EMI: <span id="emiValue">₹0.00</span>
        </div>
    </div>

    <!-- JavaScript for EMI Calculation -->
    <script>
        function calculateEMI() {
            // Get input values
            const loanAmount = parseFloat(document.getElementById('loanAmount').value);
            const interestRate = parseFloat(document.getElementById('interestRate').value);
            const loanTenure = parseFloat(document.getElementById('loanTenure').value);

            // Validate inputs
            if (isNaN(loanAmount) || isNaN(interestRate) || isNaN(loanTenure)) {
                alert("Please enter valid numbers for all fields.");
                return;
            }

            // Convert annual interest rate to monthly and percentage to decimal
            const monthlyInterestRate = (interestRate / 12) / 100;

            // Calculate EMI using the formula
            const emi = (loanAmount * monthlyInterestRate * Math.pow(1 + monthlyInterestRate, loanTenure)) /
                        (Math.pow(1 + monthlyInterestRate, loanTenure) - 1);

            // Display the result
            document.getElementById('emiValue').textContent = `₹${emi.toFixed(2)}`;
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('includes/footer.html'); ?>

</body>

</html>