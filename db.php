<?php
$host = 'localhost';
$db = 'ecommerce';
$user = 'root'; // Default XAMPP user
$pass = ''; // Default XAMPP password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
