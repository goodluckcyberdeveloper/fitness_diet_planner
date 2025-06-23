<?php
session_start();
include "config.php";

// Check if user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$name = $_SESSION["name"] ?? "Admin";

// Fetch all users with type 'normal' or 'patient'
$stmt = $conn->prepare("SELECT id, name, email, type, diseases, diet_plan, exercise_plan FROM users WHERE type IN ('normal', 'patient')");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Progress - Fitness and Diet Planner System</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('image2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #fff;
        }
        .header {
            background-color: #27ae60;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            display: flex;
            flex: 1;
            margin: 20px;
        }
        .sidebar {
            background-color: rgba(46, 125, 50, 0.95);
            color: white;
            width: 250px;
            padding: 20px;
            border-radius: 8px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .sidebar a:hover { background-color: #219653; }
        .main {
            flex: 1;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            margin-left: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #27ae60;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #27ae60;
            color: white;
        }
        tr:nth-child(even) {
            background-color: rgba(242, 242, 242, 0.95);
        }
        .logout {
            color: red;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 150px;
            }
            .main {
                margin-left: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fitness and Diet Planner System</h1>
        <h2>Welcome Admin <?php echo htmlspecialchars($name); ?>!</h2>
    </div>
    <div class="container">
        <div class="sidebar">
            <h3>Admin Menu</h3>
            <a href="admin_dashboard.php">👑 Manage Users</a>
            <a href="view_all_progress.php">📈 View All Progress</a>
            <a href="logout.php" class="logout">🚪 Logout</a>
        </div>
        <div class="main">
            <h3>Progress of Normal and Patient Users</h3>
            <?php if (empty($users)): ?>
                <p>No normal or patient users found.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Diseases</th>
                        <th>Diet Plan</th>
                        <th>Exercise Plan</th>
                    </tr>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['type']); ?></td>
                            <td><?php echo htmlspecialchars($user['diseases'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['diet_plan'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['exercise_plan'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>