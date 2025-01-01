<?php
$servername = "165.22.247.129";
$username = "root";
$password = "202214324";
$dbname = "gym";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "banana";
?>
