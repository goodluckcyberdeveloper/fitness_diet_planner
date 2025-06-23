<?php
// Taarifa za database
$servername = "localhost";
$username = "root";
$password = ""; // Acha tupu kwa XAMPP
$dbname = "fitness_diet_planner"; // Hakikisha hii ni database yako halisi

// Jaribu kuunganisha
$conn = new mysqli($servername, $username, $password, $dbname);

// Angalia kama kuna kosa
$conn = mysqli_connect("localhost", "root", "", "fitness_diet_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




?>
