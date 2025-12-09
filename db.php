<?php
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";  // Default XAMPP password is empty
$database = "contact_manager";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
