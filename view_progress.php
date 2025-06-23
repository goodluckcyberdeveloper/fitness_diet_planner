<?php
session_start();
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login_form.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT progress_date, diet_followed, exercise_done, notes FROM user_progress WHERE user_id = $user_id ORDER BY progress_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Historia ya Maendeleo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php">🏠 Mwanzo</a>
    <a href="record_progress.php">📝 Rekodi Maendeleo</a>
    <a href="view_progress.php">📊 Historia</a>
    <a href="meal_plan.php">🥗 Mpango wa Chakula</a>
    <a href="exercise_plan.php">🏋️ Mazoezi</a>
    <a href="logout.php" class="logout">🚪 Logout</a>
</div>

<div class="content">
    <h3>📊 Historia ya Maendeleo</h3>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Tarehe</th>
                <th>Chakula?</th>
                <th>Mazoezi?</th>
                <th>Maelezo</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["progress_date"]; ?></td>
                    <td><?php echo $row["diet_followed"]; ?></td>
                    <td><?php echo $row["exercise_done"]; ?></td>
                    <td><?php echo htmlspecialchars($row["notes"]); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Hujarekodi maendeleo yoyote bado.</p>
    <?php endif; ?>
</div>
</body>
</html>
