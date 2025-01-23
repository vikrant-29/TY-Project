<?php
include('../includes/header.html');
include('../includes/connect.php');
if(isset($_POST['submit']))
{
    $nm =$_POST['admin_nm'];
    $pss =$_POST['admin_pss'];
    $query_1 = $query_1 = "SELECT username, pass FROM admin WHERE username = '$nm' AND pass = '$pss'";

    $myres = mysqli_query($conn,$query_1);
    if(mysqli_num_rows($myres)>0)
    {
        header('location:dashboard.php');
    }
    else
    {
        echo "<script>alert('username/paasword inccorect')</script>"; 
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Admin Login</title>
</head>

<body>
    <style>
        body
        {
            background-image: linear-gradient(to bottom right, #00f260, #0575e6);
    background-attachment: fixed; /* Keep the background fixed when scrolling */
        }
        .container{
            width: 600px;
        }


    </style>
    <div class="container my-5 shadow-lg p-3 mb-5 bg-white rounded">
        <form action="admin_login.php" method=POST>
            <div class="md-3 form-group ">
                <label>username</label>
                <input type="email" class="form-control" name="admin_nm">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="admin_pss">
            </div>

            <input type="submit" value="login" name="submit" class="btn btn-primary">
        </form>
    </div>
</body>

</html>