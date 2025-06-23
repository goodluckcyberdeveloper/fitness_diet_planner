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

// Fetch all users for admin view
$stmt_users = $conn->prepare("SELECT id, name, email, role, type FROM users");
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$users = $result_users->fetch_all(MYSQLI_ASSOC);
$stmt_users->close();

// Fetch all feedback
$stmt_feedback = $conn->prepare("SELECT f.id AS id, f.feedback_text AS comment_text, f.created_at, u.name AS user_name, u.email 
                                 FROM feedback f 
                                 JOIN users u ON f.user_id = u.id 
                                 ORDER BY f.created_at DESC");
$stmt_feedback->execute();
$result_feedback = $stmt_feedback->get_result();
$feedback = $result_feedback->fetch_all(MYSQLI_ASSOC);
$stmt_feedback->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Fitness and Diet Planner System</title>
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
        <h2>Welcome  <?php echo htmlspecialchars($name); ?>!</h2>
    </div>
    <div class="container">
        <div class="sidebar">
            <h3>Admin Menu</h3>
            <a href="admin_dashboard.php">👑 Manage Users</a>
            <a href="view_all_progress.php">📈 View All Progress</a>
            <a href="#comments">💬 View User Feedback</a>
            <a href="logout.php" class="logout">🚪 Logout</a>
        </div>
        <div class="main">
            <h3>Users Management</h3>
            <?php if (empty($users)): ?>
                <p>No users found.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Type</th>
                    </tr>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo htmlspecialchars($user['type'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <h3 id="comments">User Feedback</h3>
            <?php if (empty($feedback)): ?>
                <p>No feedback found.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Feedback</th>
                        <th>Created At</th>
                    </tr>
                    <?php foreach ($feedback as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['email']); ?></td>
                            <td><?php echo htmlspecialchars($item['comment_text']); ?></td>
                            <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>