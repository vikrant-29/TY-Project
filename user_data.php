<?php
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
        echo "<script>alert('Aadhar number already exists. Please use a different Aadhar number.'); window.location.href = 'registration_page.php';</script>";
    }
    // If PAN number already exists
    elseif (mysqli_num_rows($panResult) > 0) {
        echo "<script>alert('PAN number already exists. Please use a different PAN number.'); window.location.href = 'registration_page.php';</script>";
    }
    else {

    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $maritalStatus = $_POST['maritalStatus'];

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
    $query = "INSERT INTO users (firstName, lastName, userName, pass, email, phoneNumber, gender, dob, maritalStatus, resi_address, corr_address, aadharNo, panNo, photo, sign, documentType, documentFile) 
              VALUES ('$firstName', '$lastName', '$userName', '$password', '$email', '$phoneNumber', '$gender', '$dob', '$maritalStatus', '$resi_address', '$corr_address', '$aadharNo', '$panNo', '$photo', '$sign', '$documentType', '$documentFile')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('User data saved successfully.'); window.location.href = 'user_data.php';</script>";
    } else {
        echo "<script>alert('Error:'); window.location.href = 'registration_page.php';</script>";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
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
            <label for="step1">Personal Details</label>
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
                <label for="step2">Next</label>
            </div>

            <!-- Step 2: Address Details -->
            <input type="radio" id="step2" name="step" hidden>
            <label for="step2">Address Details</label>
            <div class="step">
                <h3>Address Details</h3>
                <input type="text" name="addressLine1" placeholder="Address Line 1" required>
                <input type="text" name="addressLine2" placeholder="Address Line 2" >
                <input type="text" name="addressLine3" placeholder="Address Line 3" >
                <h3>Corresponding Address Details</h3>
                <input type="text" name="correspondingLine1" placeholder="Address Line 1" required>
                <input type="text" name="correspondingLine2" placeholder="Address Line 2" >
                <input type="text" name="correspondingLine3" placeholder="Address Line 3" >
                <label for="step1">Previous</label>
            </div>

            <!-- Step 3: Identification Details -->
            <input type="radio" id="step3" name="step" hidden>
            <label for="step3">Identification Details</label>
            <div class="step">
                <h3>Identification Details</h3>
                <input type="text" name="aadharNo" placeholder="Enter Aadhar Number" required>
                <input type="text" name="panNo" placeholder="Enter PAN Number" required>
                <span>Enter your Photo: </span>
                <input type="file" name="photo" accept=".jpg, .jpeg, .png, .pdf">
                <span>Enter your Signature: </span>
                <input type="file" name="sign" accept=".jpg, .jpeg, .png, .pdf">
                <label for="step2">Previous</label>
            </div>

            <!-- Step 4: Documents -->
            <input type="radio" id="step4" name="step" hidden>
            <label for="step4">Documents</label>
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
                    <label for="step3">Previous</label>
                </div>
            </div>
            <input type="submit" value="submit" name="sub_btn">
        </form>
    </div>

</body>

</html>