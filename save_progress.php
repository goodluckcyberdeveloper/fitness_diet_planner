<?php
session_start();
include "config.php"; // Hakikisha inaunganisha DB

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Pokea data
$date = $_POST["date"] ?? '';
$weight = $_POST["weight"] ?? '';
$bp = $_POST["bp"] ?? '';
$comment = $_POST["comment"] ?? '';

if ($date && $weight && $bp) {
    $stmt = $conn->prepare("INSERT INTO progress (user_id, date, weight, bp, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $date, $weight, $bp, $comment);

    if ($stmt->execute()) {
        echo "<script>alert('Maendeleo yamehifadhiwa vizuri!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Kuna tatizo: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "<script>alert('Tafadhali jaza sehemu zote.'); history.back();</script>";
}

$conn->close();
?>
