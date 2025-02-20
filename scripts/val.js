function valname(nm) {
    ob = new XMLHttpRequest();
    ob.open("GET", "/Bank_Management_System/scripts/val.php?nm=" + nm + "");
    ob.send();
    ob.onreadystatechange = function () {
        if (ob.readyState == 4) {
            document.getElementById("valname").innerHTML = ob.responseText;

        }
    }
}

function val_lname(nm) {
    ob = new XMLHttpRequest();
    ob.open("GET", "/Bank_Management_System/scripts/val.php?lnm=" + nm + "");
    ob.send();
    ob.onreadystatechange = function () {
        if (ob.readyState == 4) {
            document.getElementById("val_lname").innerHTML = ob.responseText;

        }
    }
}

function val_unm(nm) {
    ob = new XMLHttpRequest();
    ob.open("GET", "/Bank_Management_System/scripts/val.php?username=" + nm + "");
    ob.send();
    ob.onreadystatechange = function () {
        if (ob.readyState == 4) {
            document.getElementById("user_nm").innerHTML = ob.responseText;

        }
    }
}
function pass(nm) {
    ob = new XMLHttpRequest();
    ob.open("GET", "/Bank_Management_System/scripts/val.php?pass=" + nm + "");
    ob.send();
    ob.onreadystatechange = function () {
        if (ob.readyState == 4) {
            document.getElementById("pass").innerHTML = ob.responseText;

        }
    }
}

function con_pass(nm) {
    var ps = document.getElementsByName('password')[0].value;
    var ob = new XMLHttpRequest();
    ob.open("GET", "/Bank_Management_System/scripts/val.php?cpassw=" + nm + "&passw=" + ps);
    ob.send();
    ob.onreadystatechange = function () {
        if (ob.readyState == 4 && ob.status == 200) {
            document.getElementById("c_p").innerHTML = ob.responseText;
        }
    }
}

function email_val(nm) {
    ob = new XMLHttpRequest();
    ob.open("GET", "/Bank_Management_System/scripts/val.php?email=" + nm + "");
    ob.send();
    ob.onreadystatechange = function () {
        if (ob.readyState == 4) {
            document.getElementById("email_val").innerHTML = ob.responseText;

        }
    }
}

function phone_no(nm) {
    var ob = new XMLHttpRequest();
    // Send the request to PHP with the phone number
    ob.open("GET", "/Bank_Management_System/scripts/val.php?phone=" + nm);
    ob.send();

    // Handle the response
    ob.onreadystatechange = function () {
        if (ob.readyState == 4) {
            // Display the response text (valid/invalid message) in the #phone element
            document.getElementById("phone_no").innerHTML = ob.responseText;
        }
    }
}

function submit_form() {
    var fnm = document.getElementsByName('firstName')[0].value;
    var lastName = document.getElementsByName('lastName')[0].value;
    var username = document.getElementsByName('userName')[0].value;
    var password = document.getElementsByName('password')[0].value;
    var confirmPassword = document.getElementsByName('c_pass')[0].value;
    var email = document.getElementsByName('email')[0].value;
    var phoneNo = document.getElementsByName('PhoneNo')[0].value;

    let spanText;
    ob = new XMLHttpRequest();
    var url = "/Bank_Management_System/scripts/val.php?sub=1" +
        "&1nm=" + encodeURIComponent(fnm) +
        "&1lnm=" + encodeURIComponent(lastName) +
        "&1username=" + encodeURIComponent(username) +
        "&1pass=" + encodeURIComponent(password) +
        "&1passw=" + encodeURIComponent(password) +  // Assuming confirmPassword is the same as password
        "&1cpassw=" + encodeURIComponent(confirmPassword) +
        "&1email=" + encodeURIComponent(email) +
        "&1phone=" + encodeURIComponent(phoneNo);

    // Send the GET request with the parameters in the URL
    ob.open("GET", url, true);
    ob.send();
    
    ob.onreadystatechange = function () {
        if (ob.readyState == 4) {
            document.getElementById("nnn").innerHTML = ob.responseText;
            spanText = document.getElementById("nnn").innerText;
            if (spanText === "valid") {
                // Redirect to the user_data.php
                window.location.href = "user_data.php";
            }
            else
            {
                alert("Fill All fields correctly");
            }
            
        }
    }
   
    // Output: Hello, world!

    
    //console.log(spanText); // Output: Hello, world!
}

