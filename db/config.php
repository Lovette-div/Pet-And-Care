<?php
$servername = 'localhost';
$user = 'lovette.philips';
$password = 'Luny@19.com';
$dbname = 'webtech_fall2024_lovette_philips';



$conn = mysqli_connect($servername, $user, $password, $dbname) or die('Unable to connect'); // If the connection fails, display an error message

// Check if the connection has an error and handle it
if ($conn->connect_error) {
    die("Connection failed: ");
}
?>
