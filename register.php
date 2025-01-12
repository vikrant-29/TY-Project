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
    <input type="text" name = "firstName" placeholder="First Name" requrired ><br>
    <input type ="text" name = "lastName" palceholder="Last Name" required> <br>
    <input type ="text" name = "userName" palceholder="User Name" required> <br>
    <input type ="text" name = "password" palceholder="Password Name" required> <br>
    <input type ="text" name = "email" palceholder="Email Id" required> <br>
    <input type ="text" name = "phoneNumber" palceholder="Phone Number" required> 
    <input type ="submit" name = "register" value = "register">
</body>
</html>