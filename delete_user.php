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

// Prevent admin from deleting themselves
if ($user_id == $_SESSION["user_id"]) {
    $_SESSION['error'] = "You cannot delete your own account.";
    header("Location: admin_dashboard.php");
    exit();
}

// Delete user from the database
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting user.";
}

$stmt->close();
$conn->close();

header("Location: admin_dashboard.php");
exit();
?>