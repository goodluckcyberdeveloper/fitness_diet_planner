<?php
session_start();
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT * FROM progress WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Historia Yangu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #c8e6c9; /* Green background */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background-color: #2e7d32; /* Green header */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .container {
            display: flex;
            flex: 1;
            margin: 20px;
        }
        .main {
            flex: 1;
            padding: 30px;
            background-color: #c8e6c9; /* Green content area */
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .progress-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff; /* White table background for contrast */
        }
        .progress-table th,
        .progress-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #81c784; /* Green border */
        }
        .progress-table th {
            background-color: #2e7d32; /* Green header row */
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historia Yangu</h1>
    </div>

    <div class="container">
        <div class="main">
            <div class="progress-table-container">
                <table class="progress-table">
                    <tr><th>Tarehe</th><th>Maelezo</th></tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td><?= htmlspecialchars($row['progress_details']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>