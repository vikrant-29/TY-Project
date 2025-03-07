<?php

session_start();
include('includes/connect.php');
include('includes/header.html');  // Update this path if needed

if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

// Fetching user details from the session
$firstName = $_SESSION['nm'];
$lastName = $_SESSION['lastName'];
$balance=$_SESSION['balance'];
$userName = $_SESSION['user_nm'];
$email = $_SESSION['email'];
$phoneNumber = $_SESSION['mob'];
$user_id = $_SESSION['id'];  // This is the user_id from the session

if (isset($_POST['sub_btn'])) {
    // Loan Details
    $loanType = $_POST['loanType'];
    $loanAmount = $_POST['loanAmount'];
    $loanPurpose = $_POST['loanPurpose'];
    $employmentStatus = $_POST['employmentStatus'];
    $annualIncome = $_POST['annualIncome'];
    $creditScore = $_POST['creditScore'];

    // File Uploads
    $incomeProof = $_FILES['incomeProof']['name'];
    $identityProof = $_FILES['identityProof']['name'];

    // Move uploaded files to appropriate directories
    move_uploaded_file($_FILES['incomeProof']['tmp_name'], 'uimg/' . $incomeProof);
    move_uploaded_file($_FILES['identityProof']['tmp_name'], 'uimg/' . $identityProof);

    // SQL query to insert loan application data into the database
    $query = "INSERT INTO loan_applications (user_id, firstName, lastName, userName, email, phoneNumber, loanType, loanAmount, loanPurpose, employmentStatus, annualIncome, creditScore, incomeProof, identityProof) 
              VALUES ('$user_id', '$firstName', '$lastName', '$userName', '$email', '$phoneNumber', '$loanType', '$loanAmount', '$loanPurpose', '$employmentStatus', '$annualIncome', '$creditScore', '$incomeProof', '$identityProof')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Loan application submitted successfully.'); window.location.href = 'loan_status.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'loan.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application - Horizon Bank</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
     <!-- Font Awesome for Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>

    .form-container {
        width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 217, 255, 0.5); /* Neon blue shadow */
        backdrop-filter: blur(10px);
        color: #d1f0ff; /* Light blue text */
    }

    .form-container h2 {
        color: #00d9ff; /* Neon blue color */
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0, 217, 255, 0.5); /* Neon glow effect */
        margin-bottom: 20px;
    }

    .form-container label {
        font-weight: bold;
        color: #00d9ff; /* Neon blue color */
    }

    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container input[type="date"],
    .form-container select,
    .form-container textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #00d9ff; /* Neon blue border */
        border-radius: 5px;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
        color: #d1f0ff; /* Light blue text */
        font-size: 16px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container input[type="text"]:focus,
    .form-container input[type="number"]:focus,
    .form-container input[type="date"]:focus,
    .form-container select:focus,
    .form-container textarea:focus {
        border-color: #ff0077; /* Neon pink on focus */
        box-shadow: 0 0 10px rgba(255, 0, 119, 0.5); /* Neon pink glow */
        outline: none;
    }

    .form-container textarea {
        resize: vertical;
    }

    .form-container input[type="file"] {
        margin-bottom: 15px;
        color: #d1f0ff; /* Light blue text */
    }

    .form-container .btn-primary {
        background-color: #00d9ff; /* Neon blue color */
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        color: black; /* Dark text for contrast */
        font-weight: bold;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container .btn-primary:hover {
        background-color: #ff0077; /* Neon pink on hover */
        box-shadow: 0 0 15px rgba(255, 0, 119, 0.8); /* Neon pink glow */
        color: white; /* White text on hover */
    }

    .form-container button a {
        color: black; /* Dark text for contrast */
        text-decoration: none;
    }

    .form-container button {
        background-color: #00d9ff; /* Neon blue color */
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        color: black; /* Dark text for contrast */
        font-weight: bold;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container button:hover {
        background-color: #ff0077; /* Neon pink on hover */
        box-shadow: 0 0 15px rgba(255, 0, 119, 0.8); /* Neon pink glow */
        color: white; /* White text on hover */
    }

    .form-container button:hover a {
        color: white; /* White text on hover */
    }
</style>
<link rel="stylesheet" href="style/u_dashboard.css">
</head>

<body>
<?php include('includes/navbar.php');?>


    <div class="form-container">
        <a href="loan_status.php">Loan Status</a>
        <h2>Loan Application Form</h2>
        <form action="loan.php" method="POST" enctype="multipart/form-data">
            <!-- Loan Type -->
            <div class="form-group">
                <label for="loanType">Loan Type</label>
                <select name="loanType" id="loanType" required>
                    <option value="Home Loan">🏡 Home Loan</option>
                    <option value="Auto Loan">🚗 Auto Loan</option>
                    <option value="Education Loan">🎓 Education Loan</option>
                    <option value="Personal Loan">💰 Personal Loan</option>
                    <option value="Business Loan">🏢 Business Loan</option>
                </select>
            </div>

            <!-- Loan Amount -->
            <div class="form-group">
                <label for="loanAmount">Loan Amount (in ₹)</label>
                <input type="number" name="loanAmount" id="loanAmount" placeholder="Enter loan amount" required>
            </div>

            <!-- Loan Purpose -->
            <div class="form-group">
                <label for="loanPurpose">Loan Purpose</label>
                <textarea name="loanPurpose" id="loanPurpose" rows="3" placeholder="Describe the purpose of the loan" required></textarea>
            </div>

            <!-- Employment Status -->
            <div class="form-group">
                <label for="employmentStatus">Employment Status</label>
                <select name="employmentStatus" id="employmentStatus" required>
                    <option value="Employed">Employed</option>
                    <option value="Self-Employed">Self-Employed</option>
                    <option value="Unemployed">Unemployed</option>
                </select>
            </div>

            <!-- Annual Income -->
            <div class="form-group">
                <label for="annualIncome">Annual Income (in ₹)</label>
                <input type="number" name="annualIncome" id="annualIncome" placeholder="Enter annual income" required>
            </div>

            <!-- Credit Score -->
            <div class="form-group">
                <label for="creditScore">Credit Score</label>
                <input type="number" name="creditScore" id="creditScore" placeholder="Enter credit score" required>
            </div>

            <!-- Income Proof -->
            <div class="form-group">
                <label for="incomeProof">Income Proof (PDF/Image)</label>
                <input type="file" name="incomeProof" id="incomeProof" accept=".jpg, .jpeg, .png, .pdf" required>
            </div>

            <!-- Identity Proof -->
            <div class="form-group">
                <label for="identityProof">Identity Proof (PDF/Image)</label>
                <input type="file" name="identityProof" id="identityProof" accept=".jpg, .jpeg, .png, .pdf" required>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" name="sub_btn" value="Submit Application" class="btn btn-primary">
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php include('includes/footer.html'); ?>
</body>

</html>
