<?php
session_start();
include "config.php"; // Ensure database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to dashboard if user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness and Diet Planner System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-image: url('image2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #fff;
        }
        .header {
            background: linear-gradient(135deg, #27ae60, #219653);
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            font-size: 28px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-bottom: 3px solid #1b5e20;
            animation: fadeIn 1s ease-in;
        }
        .header h1 {
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .container {
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: center;
            margin: 20px;
        }
        .main {
            max-width: 600px;
            padding: 30px;
            background-color: rgba(200, 230, 201, 0.95);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            text-align: center;
        }
        .main h2 {
            font-size: 24px;
            color: #27ae60;
            margin-bottom: 20px;
        }
        .main p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .main a {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            transition: background-color 0.3s;
        }
        .main a:hover {
            background-color: #219653;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 600px) {
            .header { font-size: 24px; padding: 20px; }
            .header h1 { font-size: 28px; }
            .main { padding: 20px; max-width: 90%; }
            .main h2 { font-size: 20px; }
            .main a { padding: 8px 16px; font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fitness and Diet Planner System</h1>
    </div>

    <div class="container">
        <div class="main">
            <h2>Welcome to Your Fitness Journey!</h2>
            <p>Track your health, plan your meals, and follow personalized exercise routines with the Fitness and Diet Planner System.</p>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>