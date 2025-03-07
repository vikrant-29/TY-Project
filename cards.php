<?php
session_start();
include('includes/header.html');
include('includes/connect.php');  

if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

$account_number = $_SESSION['acc_no'];
$balance = $_SESSION['balance'];
$email = $_SESSION['email'];
$name = $_SESSION['nm'];
$mobile_number = $_SESSION['mob'];
$user_id = $_SESSION['id'];
$profile_img = $_SESSION['photo'];

if (isset($_POST['sub_btn'])) {
    // Card Details
    $cardType = $_POST['cardType'];
    $cardLimit = $_POST['cardLimit'];
    $employmentStatus = $_POST['employmentStatus'];
    $annualIncome = $_POST['annualIncome'];
    $creditScore = $_POST['creditScore'];

    // Personal Details
    $firstName = $_SESSION['first_name'];  // Assuming you have first name stored in session
    $lastName = $_SESSION['lastName'];    // Assuming you have last name stored in session
    $userName = $_SESSION['nm'];      // Assuming you have user name stored in session
    $phoneNumber = $_SESSION['mob'];       // Assuming mobile number is stored in session

    // File Uploads
    $incomeProof = $_FILES['incomeProof']['name'];
    $identityProof = $_FILES['identityProof']['name'];

    // Move uploaded files to appropriate directories
    move_uploaded_file($_FILES['incomeProof']['tmp_name'], 'uimg/' . $incomeProof);
    move_uploaded_file($_FILES['identityProof']['tmp_name'], 'uimg/' . $identityProof);

    // SQL query to insert card application data into the database
    $query = "INSERT INTO card_applications (user_id, firstName, lastName, userName, email, phoneNumber, cardType, cardLimit, employmentStatus, annualIncome, creditScore, incomeProof, identityProof) 
              VALUES ('$user_id', '$firstName', '$lastName', '$userName', '$email', '$phoneNumber', '$cardType', '$cardLimit', '$employmentStatus', '$annualIncome', '$creditScore', '$incomeProof', '$identityProof')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Card application submitted successfully.'); window.location.href = 'u_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'cards.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Application - Horizon Bank</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/u_dashboard.css">
    <!-- Custom CSS -->
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/Bank_Management_System/bank_img/back3.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        color: #d1f0ff; /* Light blue text */
        margin: 0;
        padding: 0;
    }

    .form-container {
        max-width: 800px;
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

    .form-container a {
        color: #00d9ff; /* Neon blue color */
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s ease, text-shadow 0.3s ease;
    }

    .form-container a:hover {
        color: #ff0077; /* Neon pink on hover */
        text-shadow: 0 0 10px rgba(255, 0, 119, 0.5); /* Neon pink glow */
    }
</style>
</head>

<body>
<?php include('includes/navbar.php');?>

    <div class="form-container">
        <a href="card_status.php">Check Status</a>
        <h2>Card Application Form</h2>
        <form action="cards.php" method="POST" enctype="multipart/form-data">
            <!-- Card Type -->
            <div class="form-group">
                <label for="cardType">Card Type</label>
                <select name="cardType" id="cardType" required>
                    <option value="Debit Card">💳 Debit Card</option>
                    <option value="Credit Card">💳 Credit Card</option>
                </select>
            </div>

            <!-- Card Limit -->
            <div class="form-group">
                <label for="cardLimit">Requested Card Limit (in ₹)</label>
                <input type="number" name="cardLimit" id="cardLimit" placeholder="Enter card limit" required>
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
