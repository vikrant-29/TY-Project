<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>
<h2>Create New Account</h2>   
    <form action = user_data.php method="POST">
    <input type="text" name = "firstName" placeholder="First Name" requrired><br>
    <input type ="text" name = "lastName" placeholder="Last Name" required><br>
    <input type ="text" name = "userName" placeholder="Username" required> <br>
    <input type ="text" name = "password" placeholder="Password" required> <br>
    <input type ="text" name = "email" placeholder="Email" required> <br>
    <input type ="text" name = "phoneNumber" placeholder="PhoneNo" required><br> 
    <input type ="submit" name = "register" value = "register"><br>
    </form>
</body>
</html>