<?php
session_start();
include "configure.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Mtumiaji hajapatikana.";
    exit();
}

$user_id = intval($_GET['id']);
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Mtumiaji hajapatikana.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Mtumiaji - <?= htmlspecialchars($user['name']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
        }
        p {
            margin: 8px 0;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="card">
    <h2><?= htmlspecialchars($user['name']) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Aina:</strong> <?= htmlspecialchars($user['type']) ?></p>
    <p><strong>Magonjwa:</strong> <?= htmlspecialchars($user['diseases']) ?></p>
    <p><strong>Alichaguliwa:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
    <a href="admin_dashboard.php">⬅ Rudi Dashboard</a>
</div>
</body>
</html>
