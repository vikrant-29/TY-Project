<?php
ob_start();

session_start();
include('includes/connect.php');
include('includes/header.html');

if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}

// Fetching user details from the session
$firstName = $_SESSION['nm'];
$lastName = $_SESSION['lastName'];
$userName = $_SESSION['user_nm'];
$balance = $_SESSION['balance'];
$email = $_SESSION['email'];
$phoneNumber = $_SESSION['mob'];
$user_id = $_SESSION['id'];  // This will link the application to the user

if (isset($_POST['sub_btn'])) {
    // Investment Details
    $investmentType = $_POST['investmentType'];
    $investmentAmount = $_POST['investmentAmount'];
    $investmentDuration = $_POST['investmentDuration'];
    $riskAppetite = $_POST['riskAppetite'];

    // File Upload (Optional)
    $identityProof = $_FILES['identityProof']['name'];
    if ($identityProof) {
        move_uploaded_file($_FILES['identityProof']['tmp_name'], 'uploads/' . $identityProof);
    }

    // SQL query to insert investment application data into the database
    $query = "INSERT INTO investment_applications (user_id, firstName, lastName, userName, email, phoneNumber, investmentType, investmentAmount, investmentDuration, riskAppetite, identityProof, status) 
              VALUES ('$user_id', '$firstName', '$lastName', '$userName', '$email', '$phoneNumber', '$investmentType', '$investmentAmount', '$investmentDuration', '$riskAppetite', '$identityProof', 'pending')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Investment application submitted successfully.'); window.location.href = 'invest_status.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'invest.php';</script>";
    }
}

ob_end_flush();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutual Fund Investment - Horizon Bank</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
      <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/Bank_Management_System/bank_img/back3.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            color: #d1f0ff;
            /* Light blue text */
        }

        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.7);
            /* Semi-transparent dark background */
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 217, 255, 0.5);
            /* Neon blue shadow */
            backdrop-filter: blur(10px);
            color: #d1f0ff;
            /* Light blue text */
        }

        .form-container h2 {
            color: #00d9ff;
            /* Neon blue color */
            margin-bottom: 20px;
            text-transform: uppercase;
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
            /* Neon glow effect */
        }

        .form-container label {
            font-weight: bold;
            color: #00d9ff;
            /* Neon blue color */
        }

        .form-container input[type="number"],
        .form-container input[type="text"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #00d9ff;
            /* Neon blue border */
            border-radius: 5px;
            font-size: 16px;
            background: rgba(0, 0, 0, 0.5);
            /* Semi-transparent dark background */
            color: #d1f0ff;
            /* Light blue text */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container input[type="number"]:focus,
        .form-container input[type="text"]:focus,
        .form-container select:focus {
            border-color: #ff0077;
            /* Neon pink on focus */
            box-shadow: 0 0 10px rgba(255, 0, 119, 0.5);
            /* Neon pink glow */
            outline: none;
        }

        .form-container input[type="file"] {
            margin-bottom: 15px;
            color: #d1f0ff;
            /* Light blue text */
        }

        .form-container .btn-primary {
            background-color: #00d9ff;
            /* Neon blue color */
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            color: black;
            /* Dark text for contrast */
            font-weight: bold;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container .btn-primary:hover {
            background-color: #ff0077;
            /* Neon pink on hover */
            box-shadow: 0 0 15px rgba(255, 0, 119, 0.8);
            /* Neon pink glow */
            color: white;
            /* White text on hover */
        }

        .form-container button a {
            color: black;
            /* Dark text for contrast */
            text-decoration: none;
        }

        .form-container button:hover {
            background-color: #ff0077;
            /* Neon pink on hover */
            box-shadow: 0 0 15px rgba(255, 0, 119, 0.8);
            /* Neon pink glow */
            color: white;
            /* White text on hover */
        }

        .form-container button:hover a {
            color: white;
            /* White text on hover */
        }
    </style>
    <link rel="stylesheet" href="style/u_dashboard.css">
</head>

<body>
<?php include('includes/navbar.php');?>

    <div class="form-container">
    <a href="invest_status.php">Check Status</a>
        <h2>Mutual Fund Investment Application</h2>
        <!-- Add this line inside the form or at the top of the investment.php page -->
        <div class="form-group">
            <p>New to mutual funds? <a href="blog_mutual_funds.html" target="_blank">Learn more about mutual funds</a>.</p>
        </div>
        <form action="invest.php" method="POST" enctype="multipart/form-data">
            <!-- Investment Type -->
            <div class="form-group">
                <label for="investmentType">Investment Type</label>
                <select name="investmentType" id="investmentType" required>
                    <option value="Equity Mutual Fund">Equity Mutual Fund</option>
                    <option value="Debt Mutual Fund">Debt Mutual Fund</option>
                    <option value="Hybrid Mutual Fund">Hybrid Mutual Fund</option>
                    <option value="Index Mutual Fund">Index Mutual Fund</option>
                </select>
            </div>

            <!-- Investment Amount -->
            <div class="form-group">
                <label for="investmentAmount">Investment Amount (in ₹)</label>
                <input type="number" name="investmentAmount" id="investmentAmount" placeholder="Enter investment amount" required>
            </div>

            <!-- Investment Duration -->
            <div class="form-group">
                <label for="investmentDuration">Investment Duration (in years)</label>
                <input type="number" name="investmentDuration" id="investmentDuration" placeholder="Enter investment duration" required>
            </div>

            <!-- Risk Appetite -->
            <div class="form-group">
                <label for="riskAppetite">Risk Appetite</label>
                <select name="riskAppetite" id="riskAppetite" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <!-- Identity Proof -->
            <div class="form-group">
                <label for="identityProof">Identity Proof (PDF/Image)</label>
                <input type="file" name="identityProof" id="identityProof" accept=".jpg, .jpeg, .png, .pdf">
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