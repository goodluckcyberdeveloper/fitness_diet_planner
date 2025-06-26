<?php
session_start();
include "config.php";

// Check if user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = $_GET['id'];

// Prevent admin from unblocking themselves
if ($user_id == $_SESSION["user_id"]) {
    $_SESSION['error'] = "You cannot unblock your own account.";
    header("Location: admin_dashboard.php");
    exit();
}

// Update user status to active
$stmt = $conn->prepare("UPDATE users SET status = 0 WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User unblocked successfully.";
} else {
    $_SESSION['error'] = "Error unblocking user.";
}

$stmt->close();
$conn->close();

header("Location: admin_dashboard.php");
exit();
?>