<?php
ob_start();

include("includes/header.html");
session_start();
include('includes/connect.php');  // Update this path if needed

$firstName =  $_SESSION['firstName'];
$lastName =  $_SESSION['lastName'];
$userName =  $_SESSION['userName'];
$password =  $_SESSION['password'];
$email =  $_SESSION['email'];
$phoneNumber =  $_SESSION['PhoneNo'];

if (isset($_POST['sub_btn'])) {
    // Personal Details

    $checkAadharQuery = "SELECT * FROM users WHERE aadharNo = '{$_POST['aadharNo']}'";
    $checkPanQuery = "SELECT * FROM users WHERE panNo = '{$_POST['panNo']}'";

    $aadharResult = mysqli_query($conn, $checkAadharQuery);
    $panResult = mysqli_query($conn, $checkPanQuery);


    // If Aadhar number already exists
    if (mysqli_num_rows($aadharResult) > 0) {
        echo "<script>alert('Aadhar number already exists. Please use a different Aadhar number.'); window.location.href = 'user_data.php';</script>";
    }
    // If PAN number already exists
    elseif (mysqli_num_rows($panResult) > 0) {
        echo "<script>alert('PAN number already exists. Please use a different PAN number.'); window.location.href = 'user_data.php';</script>";
    } else {

        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $maritalStatus = $_POST['maritalStatus'];
        $security_q =  $_POST['securityQuestion'];
        $answer = $_POST['securityAnswer'];

        // Address Details
        $adl1 = $_POST['addressLine1'];
        $adl2 = $_POST['addressLine2'];
        $adl3 = $_POST['addressLine3'];
        $resi_address = $adl1 . " " . $adl2 . " " . $adl3;

        $corr1 = $_POST['correspondingLine1'];
        $corr2 = $_POST['correspondingLine2'];
        $corr3 = $_POST['correspondingLine3'];
        $corr_address = $corr1 . " " . $corr2 . " " . $corr3;

        // Identification Details
        $aadharNo = $_POST['aadharNo'];
        $panNo = $_POST['panNo'];

        // File Uploads
        $photo = $_FILES['photo']['name'];
        $sign = $_FILES['sign']['name'];
        $documentType = $_POST['doxs'];
        $documentFile = $_FILES['document']['name'];

        // Move uploaded files to appropriate directories
        move_uploaded_file($_FILES['photo']['tmp_name'], 'uimg/' . $photo);
        move_uploaded_file($_FILES['sign']['tmp_name'], 'uimg/' . $sign);
        move_uploaded_file($_FILES['document']['tmp_name'], 'udocx/' . $documentFile);

        // SQL query to insert user data into the database
        $query = "INSERT INTO users (firstName, lastName, userName, pass, email, phoneNumber, gender, dob, maritalStatus, resi_address, corr_address, aadharNo, panNo, photo, sign, documentType, documentFile,sec_que,que_ans) 
              VALUES ('$firstName', '$lastName', '$userName', '$password', '$email', '$phoneNumber', '$gender', '$dob', '$maritalStatus', '$resi_address', '$corr_address', '$aadharNo', '$panNo', '$photo', '$sign', '$documentType', '$documentFile','$security_q','$answer')";

    
        if (mysqli_query($conn, $query)) {
            // Get the last inserted ID (User ID)
            $user_id = mysqli_insert_id($conn);
            $account_sql = "INSERT INTO accounts (id) 
                    VALUES (?)"; 

            // Prepare the query
            $stmt = $conn->prepare($account_sql);

            // Check if the statement was prepared successfully
            if ($stmt === false) {
                echo "<script>alert('Error preparing SQL statement: " . $conn->error . "'); window.location.href = 'user_data.php';</script>";
                exit();
            }

            // Bind the parameters to the prepared statement: 'i' for integer (user_id), 's' for string (account_number)
            $stmt->bind_param('i', $user_id);  // Bind user_id (int) and account_number (string)

            // Execute the query
            if ($stmt->execute()) {
                echo "<script>alert('Registered Succesfully.. !'); window.location.href = 'login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error inserting into accounts table: " . $stmt->error . "'); window.location.href = 'user_data.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Error inserting user data.'); window.location.href = 'user_data.php';</script>";
        }
    }
    echo "<script>window.location.href = 'login.php';</script>";
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<link rel="icon" href="../bank_img/logo_fade.jpeg" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css?v=1.0">

    <link rel="stylesheet" href="style/reg.css">
</head>

<body>

    <div class="form-container">
        <form action="user_data.php" method="POST" enctype="multipart/form-data">

            <!-- Step 1: Personal Details -->
            <input type="radio" id="step1" name="step" hidden>
            <div class="step">
                <h3>Personal Details</h3>
                <input type="text" name="firstName" value="<?php echo $firstName; ?>" disabled>
                <input type="text" name="lastName" value="<?php echo $lastName; ?>" disabled>
                <input type="text" name="userName" value="<?php echo $userName; ?>" disabled>
                <input type="text" name="password" value="<?php echo $password; ?>" disabled>
                <input type="text" name="email" value="<?php echo $email; ?>" disabled>
                <input type="text" name="phoneNumber" value="<?php echo $phoneNumber; ?>" disabled>
                <h3>Select your gender:</h3>
                <input type="radio" name="gender" value="Male" required> Male
                <input type="radio" name="gender" value="Female" required> Female
                <input type="radio" name="gender" value="Other" required> Other<br>
                <h3>Enter Date of Birth:</h3>
                <input type="date" name="dob" required>
                <h3>Marital Status:</h3>
                <input type="radio" name="maritalStatus" value="Married" required> Married
                <input type="radio" name="maritalStatus" value="Unmarried" required> Unmarried
                <input type="radio" name="maritalStatus" value="Divorced" required> Divorced
                <br>

                <div class="row my-3">
                <div class="col">
                    <label for="securityQuestion">Security Question</label>
                    <select name="securityQuestion" id="securityQuestion" class="form-control" required>
                        <option value="">Select a security question</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What was the name of your first school?">What was the name of your first school?</option>
                        <option value="In what city were you born?">In what city were you born?</option>
                        <option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend?</option>
                        <option value="street">What is the name of the street you grew up on?</option>
                        <option value="What is the name of the street you grew up on?">What was the name of your first car?</option>
                        <option value="What is the name of your high school?">What is the name of your high school?</option>
                        <option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
                        <option value="What was the name of your favorite teacher?">What was the name of your favorite teacher?</option>
                        <option value="What is your favorite movie?">What is your favorite movie?</option>
                        <option value="What is your favorite book?">What is your favorite book?</option>
                        <option value="What is your favorite vacation destination?">What is your favorite vacation destination?</option>
                    </select>
                </div>
            </div>
            <div class="row my-3 mx-5">
                    <label for="securityAnswer">Answer</label>
                    <input type="text" class="form-control" id="securityAnswer" name="securityAnswer" required>
            </div>

            </div>

            <!-- Step 2: Address Details -->
            <input type="radio" id="step2" name="step" hidden>
            <div class="step">
                <h3>Permanat Address Details</h3>
                <input type="text" name="addressLine1" placeholder="Address Line 1" required>
                <input type="text" name="addressLine2" placeholder="Address Line 2">
                <input type="text" name="addressLine3" placeholder="Address Line 3">
                <h3>Corresponding Address Details</h3>
                <input type="text" name="correspondingLine1" placeholder="Address Line 1" required>
                <input type="text" name="correspondingLine2" placeholder="Address Line 2">
                <input type="text" name="correspondingLine3" placeholder="Address Line 3">

            </div>

            <!-- Step 3: Identification Details -->
            <input type="radio" id="step3" name="step" hidden>
            <div class="step">
                <h3>Identification Details</h3>
                <input type="text" name="aadharNo" placeholder="Enter Aadhar Number" required>
                <input type="text" name="panNo" placeholder="Enter PAN Number" required>
                <span>Enter your Photo: </span>
                <input type="file" name="photo" accept=".jpg, .jpeg, .png, .pdf">
                <span>Enter your Signature: </span>
                <input type="file" name="sign" accept=".jpg, .jpeg, .png, .pdf">

            </div>

            <!-- Step 4: Documents -->
            <input type="radio" id="step4" name="step" hidden>
            <div class="step">
                <h3>Documents</h3>
                <select name="doxs">
                    <option value="addhar_c">Aadhar card</option>
                    <option value="pan_c">PAN card</option>
                    <option value="voter_c">Voter card</option>
                    <option value="eletric_b">Electric bill</option>
                </select>
                <input type="file" name="document" accept=".jpg, .jpeg, .png, .pdf">
                <div class="submit">

                </div>
            </div>
            <input type="submit" value="submit" name="sub_btn">
        </form>
    </div>

</body>

</html>