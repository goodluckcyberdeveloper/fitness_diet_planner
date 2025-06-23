<?php
session_start();
include "config.php"; // Ensure this matches the file name

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $progress = $_POST["progress"];
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO progress (user_id, progress_details) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $progress);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Ongeza Maendeleo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #c8e6c9; /* Green background */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2e7d32; /* Dark green for heading */
        }
        form {
            background-color: #ffffff; /* White background for form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        textarea {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #81c784; /* Green border */
            border-radius: 4px;
            resize: vertical;
        }
        button {
            background-color: #2e7d32; /* Green button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1b5e20; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <h2>Rekodi Maendeleo</h2>
    <form method="post">
        <textarea name="progress" rows="6" cols="50" required></textarea><br><br>
        <button type="submit">Wasilisha</button>
    </form>
</body>
</html>