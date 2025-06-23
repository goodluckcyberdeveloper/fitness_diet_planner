<?php
session_start();
$name = $_SESSION["name"] ?? "User";
$type = $_SESSION["type"] ?? "normal";

// Exercise recommendations
$exercises = [
    "normal" => [
        "Walk for 30 minutes daily",
        "Stretching exercises",
        "Light exercises like yoga or pilates"
    ],
    "overweight" => [
        "Brisk walking for 45 minutes",
        "Fat-burning exercises (cardio)",
        "Strength-building exercises (squats, pushups)"
    ],
    "obesity" => [
        "Low-impact exercises like swimming",
        "Slow but long-duration walking",
        "Follow home workout videos"
    ],
    "hypertension" => [
        "Relaxing exercises like yoga",
        "Regular walking",
        "Avoid high-intensity exercises"
    ],
    "underweight" => [
        "Light muscle-building exercises",
        "Yoga and relaxing exercises",
        "Exercises to stimulate appetite"
    ]
];

$list = $exercises[$type] ?? $exercises["normal"];

// Weekly schedule
$weekly_plan = [
    "Monday" => $list[0] ?? "Rest or light walking",
    "Tuesday" => $list[1] ?? "Stretching exercises",
    "Wednesday" => $list[2] ?? "Light yoga",
    "Thursday" => $list[0] ?? "Rest or light walking",
    "Friday" => $list[1] ?? "Stretching exercises",
    "Saturday" => $list[2] ?? "Light yoga",
    "Sunday" => "Rest or light walking"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exercise Plan</title>
    <style>
        body {
            background-image: url('image 4.jpg'); /* Replace with the correct image name (.jpg, .png, etc.) */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95); /* Semi-transparent white */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 800px;
            text-align: center;
            color: #333;
        }
        h3 {
            color: #27ae60; /* Green for consistency */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #27ae60; /* Green border */
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #27ae60; /* Green */
            color: white;
        }
        tr:nth-child(even) {
            background-color: rgba(242, 242, 242, 0.95); /* Semi-transparent light gray */
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #27ae60; /* Green */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #219653; /* Darker green */
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Exercise Plan for <?php echo htmlspecialchars($name); ?></h3>
        <p><strong>User Type:</strong> <?php echo htmlspecialchars($type); ?></p>
        <table>
            <tr>
                <th>Day</th>
                <th>Recommended Exercise</th>
            </tr>
            <?php foreach ($weekly_plan as $day => $exercise): ?>
                <tr>
                    <td><?php echo htmlspecialchars($day); ?></td>
                    <td><?php echo htmlspecialchars($exercise); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="dashboard.php" class="back-button">Back</a>
    </div>
</body>
</html>