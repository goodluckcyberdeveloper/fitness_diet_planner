<?php
include "config.php";

$admin_email = "admin@green.com";
$admin_password = password_hash("admin123", PASSWORD_DEFAULT); // Hashes the password
$admin_name = "Admin user";
$admin_address = "Admin Office, Tanzania";
$admin_role = "admin"; // Store "admin" in a variable

// Check if email already exists
$stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt_check->bind_param("s", $admin_email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // Email exists, update the record
    $sql = "UPDATE users SET name = ?, password = ?, address = ?, role = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $admin_name, $admin_password, $admin_address, $admin_role, $admin_email);
    if ($stmt->execute()) {
        header("Location:homepage.php");

        // echo "Admin user updated successfully!";
    } else {
        echo "Error updating admin: " . $conn->error;
    }
} else {
    // Email does not exist, insert new record
    $sql = "INSERT INTO users (name, email, password, address, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $admin_name, $admin_email, $admin_password, $admin_address, $admin_role);
    if ($stmt->execute()) {
        header("Location:homepage.php");
        // echo "Admin user added successfully!";
    } else {
        echo "Error adding admin: " . $conn->error;
    }
}

$stmt->close();
if (isset($stmt_check)) $stmt_check->close(); // Close the check statement if it was used
$conn->close();
?>