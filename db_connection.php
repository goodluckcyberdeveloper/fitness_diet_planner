<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitness_diet_planner"; // hakikisha database hii ipo kwenye phpMyAdmin

// Unda connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Angalia kama imefanikiwa
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
