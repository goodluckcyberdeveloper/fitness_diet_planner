<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Information</title>
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
            background-color: #27ae60;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header h1 { margin-bottom: 10px; }
        .header h2 { font-size: 20px; }
        .container {
            display: flex;
            flex: 1;
            margin: 20px;
            justify-content: center;
        }
        .form-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #555;
        }
        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #27ae60;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #219653;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #27ae60;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fitness and Diet Planner System</h1>
        <h2>Health Information for <?php echo htmlspecialchars($_SESSION["name"] ?? "User"); ?></h2>
    </div>

    <div class="container">
        <div class="form-box">
            <h2>Health Information</h2>
            <form action="process_health.php" method="post" id="healthForm">
                <label>Weight (kg):</label>
                <input type="number" name="weight" id="weight" placeholder="Weight (kg)" 
                       required min="20" max="300" step="0.1" />

                <label>Height (cm):</label>
                <input type="number" name="height" id="height" placeholder="Height (cm)" 
                       required min="50" max="300" step="0.1" />

                <label>Age (years):</label>
                <input type="number" name="age" id="age" placeholder="Age (years)" 
                       required min="10" max="120" />

                <label>Blood Pressure (e.g., 120/80):</label>
                <input type="text" name="blood_pressure" id="blood_pressure" 
                       placeholder="Blood Pressure (e.g., 120/80)" 
                       required pattern="\d{2,3}/\d{2,3}" title="Enter like 120/80" />

                <button type="submit">Submit Information</button>
            </form>
            <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>