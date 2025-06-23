<?php
session_start();
include "config.php";

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch all feedback with user information
$stmt = $conn->prepare("INSERT INTO feedback (user_id, feedback_text) VALUES (?, ?)");
$sql = "SELECT f.feedback_id, f.feedback_text, f.created_at, u.username, u.email 
        FROM feedback f 
        JOIN users u ON f.user_id = u.user_id 
        ORDER BY f.created_at DESC";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            float: right;
        }
    </style>
</head>
<body>
    <h2>User Feedback</h2>
    <a href="admin_logout.php" class="logout-btn">Logout</a>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Feedback</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['feedback_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No feedback found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>