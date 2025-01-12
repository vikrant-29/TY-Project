<?php
include('includes/connect.php');  // Update this path if needed

$firstName = isset($_POST['firstName']) ? $_POST['firstName'] : 'abc';
$lastName = isset($_POST['lastName']) ? $_POST['lastName'] : 'abc';
$userName = isset($_POST['userName']) ? $_POST['userName'] : 'abc';
$password = isset($_POST['password']) ? $_POST['password'] : 'abc';
$email = isset($_POST['email']) ? $_POST['email'] : 'abc';
$phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : 'abc';


if (isset($_POST['sub_btn'])) {
    // Personal Details
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
        echo "User data saved successfully.";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
    <link rel="stylesheet" href="style /reg.css">
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
                <input type="radio" name="gender" required> Male
                <input type="radio" name="gender" required> Female
                <input type="radio" name="gender" required> Other<br>
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
                <input type="text" name="addressLine2" placeholder="Address Line 2" required>
                <input type="text" name="addressLine3" placeholder="Address Line 3" required>
                <h3>Corresponding Address Details</h3>
                <input type="text" name="correspondingLine1" placeholder="Address Line 1" required>
                <input type="text" name="correspondingLine2" placeholder="Address Line 2" required>
                <input type="text" name="correspondingLine3" placeholder="Address Line 3" required>
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
                <input type="file" name="photo" accept=".jpg, .jpeg, .png, .pdf" required>
                <span>Enter your Signature: </span>
                <input type="file" name="sign" accept=".jpg, .jpeg, .png, .pdf" required>
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
                <input type="file" name="document" accept=".jpg, .jpeg, .png, .pdf" required>
                <div class="submit">
                    <label for="step3">Previous</label>
                </div>
            </div>
            <input type="submit" value="submit" name="sub_btn">
        </form>
    </div>

</body>

</html>