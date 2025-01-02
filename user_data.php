<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
</head>

<body>
<form action=user_data.php method="POST">

    <div class="personal_details">
        <h2>Personal Details</h2>
            <input type="text" name="firstName" placeholder="First Name" disabled>
            <input type="text" name="lastName" palceholder="Last Name" disabled>
            <input type="text" name="userName" palceholder="User Name" disabled>
            <input type="text" name="password" palceholder="Password Name" disabled>
            <input type="text" name="email" palceholder="Email Id" disabled>
            <input type="text" name="phoneNumber" palceholder="Phone Number" disabled>
            <input type="radio" name="gender" value="male" required> Male<br>
            <input type="radio" name="gender" value="female" required> Female<br>
            <input type="radio" name="gender" value="other" required> Other<br>
            <input type="date" name="date" required>
            <input type="radio" name="married" value="married" required> Married<br>
            <input type="radio" name="unmarried" value="unmarried" required>UnMarried<br>
            <input type="radio" name="divorced" value="divorced" required> Divorced<br>
            <!-- add next buttton -->

    </div>
    <div class="address_details">
        <h2> Address Details</h2>
        <input type="text" name="line1" placeholder="Address Line 1" required>
        <input type="text" name="line2" placeholder="Address Line 2" required>
        <input type="text" name="line3" placeholder="Address Line 3" required>
        <h3>Corressponding Adrress Details</h3>
        <input type="text" name="line1" placeholder="Address Line 1" required>
        <input type="text" name="line2" placeholder="Address Line 2" required>
        <input type="text" name="line3" placeholder="Address Line 3" required>
        <!-- state, district, taluka, pincode add later-->
    </div>

    <div class="Identfication_details">
        <h2> Identication Details</h2>
        <input type="text" name="aadharNo" placeholder="Enter Aadhar Number" required>
        <input type="text" name="panNo" placeholder="Enter Pan Number" required>
        <input type="file" id="file" name="file" accept=".jpg, .jpeg, .png .pdf" />
        <!--Photo and sign -->
    </div>

    <div class="documents">
        <h2> Documents</h2>
        <input type="file" id="file" name="file" accept=".jpg, .jpeg, .png .pdf" />
        <!--all required docs -->
        <div class="submit">
        <input type = "submit" name = "submit" value = "finishRegistration">
<!-- button for preview must be add-->
        </div>
    </div>
</form>



</body>

</html>