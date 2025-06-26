<?php
$servername = getenv("DB_HOST") ?? "127.0.0.1";
$username = getenv("DB_USER") ?? "root"; // badilisha kama unatumia jina la mtumiaji tofauti
$password = getenv("DB_PASS") ?? ""; // badilisha kama unatumia nenosiri tofauti
$dbname = getenv("DB_NAME") ?? "fitness_diet_planner"; // hakikisha database hii ipo kwenye phpMyAdmin

// Unda connection
$conn = new mysqli("localhost", "root", "", "fitness_diet_planner");

// Angalia kama imefanikiwa
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
