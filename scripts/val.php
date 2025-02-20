<?php

echo "<html>
<style>
    .valid {
        color: #39FF14;
        /* Neon green color */
        font-weight: 400;
        text-shadow: 0 0 5px #39FF14, 0 0 10px #39FF14, 0 0 20px #39FF14, 0 0 40px #00FF00, 0 0 60px #00FF00, 0 0 80px #00FF00, 0 0 100px #00FF00;
    }

    .invalid {
        color: #FF073A;
        /* Neon red color */
        font-weight: 400;
        text-shadow: 0 0 5px #FF073A, 0 0 10px #FF073A, 0 0 20px #FF073A, 0 0 40px #FF2A68, 0 0 60px #FF2A68, 0 0 80px #FF2A68, 0 0 100px #FF2A68;
    }
</style>
</html>";
?>
<?php

if (isset($_GET['nm'])) {
    $name = trim($_GET['nm']);
    if (empty($name)) {
        echo "<span class='invalid'>Name cannot be empty</span> ";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        echo "<span class='invalid'>Only letters and spaces are allowed</span>";
    } else {

        echo "<span class='valid'>Valid Name</span>";
    }
}

if (isset($_GET['lnm'])) {
    $name = trim($_GET['lnm']);
    if (empty($name)) {
        echo "<span class='invalid'>Name cannot be empty</span> ";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        echo "<span class='invalid'>Only letters and spaces are allowed</span>";
    } else {

        echo "<span class='valid'>Valid Name</span>";
    }
}

if (isset($_GET['username'])) {
    $username = trim($_GET['username']);
    if (empty($username)) {
        echo "<span class='invalid'>Username cannot be empty</span>";
    } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        echo "<span class='invalid'>Username can only contain letters, numbers, and underscores</span>";
    } elseif (strlen($username) < 5 || strlen($username) > 15) {
        echo "<span class='invalid'>Username must be between 5 and 15 characters</span>";
    } else {

        echo "<span class='valid'>Valid Username</span>";
    }
}

if (isset($_GET['pass'])) {
    $password = trim($_GET['pass']);
    if (empty($password)) {
        echo "<span class='invalid'>Password cannot be empty</span>";
    } elseif (strlen($password) < 8) {
        echo "<span class='invalid'>Password must be at least 8 characters long</span>";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
        echo "<span class='invalid'>Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character</span>";
    } else {

        echo "<span class='valid'>Valid Password</span>";
    }
}

if (isset($_GET['passw']) && isset($_GET['cpassw'])) {
    $password = trim($_GET['passw']);
    $confirmPassword = trim($_GET['cpassw']);

    if (empty($password) || empty($confirmPassword)) {
        echo "<span class='invalid'>Both password fields are required</span>";
    } elseif ($password !== $confirmPassword) {
        echo "<span class='invalid'>Passwords do not match</span>";
    } else {

        echo "<span class='valid'>Passwords match</span>";
    }
}

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    if (empty($email)) {
        echo "<span class='invalid'>Email cannot be empty</span>";
    } elseif (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/", $email)) {
        echo "<span class='invalid'>Invalid email format</span>";
    } else {

        echo "<span class='valid'>Valid Email</span>";
    }
}

if (isset($_GET['phone'])) {
    $phone = trim($_GET['phone']);
    if (empty($phone)) {
        echo "<span class='invalid'>Phone number cannot be empty</span>";
    } elseif (!preg_match("/^[789]\d{9}$/", $phone)) {
        echo "<span class='invalid'>Invalid phone number format</span>";
    } else {

        echo "<span class='valid'>Valid phone number</span>";
    }
}


if (isset($_GET['sub'])) {

    // Initialize all variables to 0
    $nm = $lnm = $username = $pass = $passw = $email = $phone = 0;

    if (isset($_GET['1nm'])) {
        $name = trim($_GET['1nm']);
        if (!empty($name) && preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nm = 1; // Set $nm to 1 for valid input
        }
    }

    if (isset($_GET['1lnm'])) {
        $name = trim($_GET['1lnm']);
        if (!empty($name) && preg_match("/^[a-zA-Z ]*$/", $name)) {
            $lnm = 1; // Set $lnm to 1 for valid input
        }
    }

    if (isset($_GET['1username'])) {
        $username = trim($_GET['1username']);
        if (!empty($username) && preg_match("/^[a-zA-Z0-9_]*$/", $username) && strlen($username) >= 5 && strlen($username) <= 15) {
            $username = 1; // Set $username to 1 for valid input
        }
    }

    if (isset($_GET['1pass'])) {
        $password = trim($_GET['1pass']);
        if (!empty($password) && strlen($password) >= 8 && preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
            $pass = 1; // Set $pass to 1 for valid input
        }
    }

    if (isset($_GET['1passw']) && isset($_GET['1cpassw'])) {
        $password = trim($_GET['1passw']);
        $confirmPassword = trim($_GET['1cpassw']);
        if (!empty($password) && !empty($confirmPassword) && $password === $confirmPassword) {
            $passw = 1; // Set $passw to 1 for valid input
        }
    }

    if (isset($_GET['1email'])) {
        $email = trim($_GET['1email']);
        if (!empty($email) && preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/", $email)) {
            $email = 1; // Set $email to 1 for valid input
        }
    }

    if (isset($_GET['1phone'])) {
        $phone = trim($_GET['1phone']);
        if (!empty($phone) && preg_match("/^[789]\d{9}$/", $phone)) {
            $phone = 1; // Set $phone to 1 for valid input
        }
    }

    // If all variables are 1, echo "All inputs are valid", else "Some inputs are invalid"
    if ($nm && $lnm && $username && $pass && $passw && $email && $phone) {
            echo "valid";
    } else {
        echo "invalid";
    }

}
?>



