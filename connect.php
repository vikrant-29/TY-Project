<?php
// MySQLi Procedural Connection
$servername = "localhost";
$username = "root"; // Default username for MySQL
$password = ""; // Default password for MySQL (usually empty in local server)
$dbname = "bank_management"; // Name of the database

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    echo "<script>alert('Failed to connect database. ');";
}
// You can remove this after testing
?>
