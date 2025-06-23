<?php
session_start();
include "config.php"; // Ensure database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$weight = null;
$height_cm = null;
$age = null;
$blood_pressure = null;
$bmi = null;
$condition = null;
$error = null;
$recommendations = [];
$status_class = "status-normal";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["weight"], $_POST["height"], $_POST["age"], $_POST["blood_pressure"])) {
    $weight = floatval($_POST["weight"]);
    $height_cm = floatval($_POST["height"]);
    $age = intval($_POST["age"]);
    $blood_pressure = $_POST["blood_pressure"];

    // Validate inputs
    if ($weight < 20 || $weight > 300) {
        $error = "Invalid weight. Must be between 20 and 300 kg.";
    } elseif ($height_cm < 50 || $height_cm > 300) {
        $error = "Invalid height. Must be between 50 and 300 cm.";
    } elseif ($age < 10 || $age > 120) {
        $error = "Invalid age. Must be between 10 and 120 years.";
    } elseif (!preg_match("/^\d{2,3}\/\d{2,3}$/", $blood_pressure)) {
        $error = "Invalid blood pressure format. Use format like 120/80.";
    } else {
        // Calculate BMI
        $height_m = $height_cm / 100;
        $bmi = round($weight / ($height_m * $height_m), 1);

        // Determine BMI condition
        if ($bmi < 18.5) {
            $condition = "Underweight";
            $recommendations[] = "Your BMI is low, indicating underweight. To gain weight healthily, follow a nutrient-rich meal plan and engage in strength-building exercises. Visit <a href='meal_plan.php'>Meal Plan</a> for a tailored diet and <a href='exercise_plan.php'>Exercise Plan</a> for suitable workouts.";
            $status_class = "status-low";
        } elseif ($bmi < 25) {
            $condition = "Normal";
            $recommendations[] = "Your BMI is normal. Maintain your health with a balanced diet and regular exercise. Visit <a href='meal_plan.php'>Meal Plan</a> for personalized meal ideas and <a href='exercise_plan.php'>Exercise Plan</a> for fitness routines to stay healthy.";
            $status_class = "status-normal";
        } elseif ($bmi < 30) {
            $condition = "Overweight";
            $recommendations[] = "Your BMI is high, indicating overweight. Adopt a balanced diet and increase physical activity to manage weight. Visit <a href='meal_plan.php'>Meal Plan</a> for a tailored diet plan and <a href='exercise_plan.php'>Exercise Plan</a> for effective exercises.";
            $status_class = "status-high";
        } else {
            $condition = "Obesity";
            $recommendations[] = "Your BMI is very high, indicating obesity. Consult a healthcare provider and follow a structured diet and exercise plan to manage weight. Visit <a href='meal_plan.php'>Meal Plan</a> for a personalized diet and <a href='exercise_plan.php'>Exercise Plan</a> for safe workouts.";
            $status_class = "status-high";
        }

        // Parse and evaluate blood pressure
        if (strpos($blood_pressure, '/') !== false) {
            list($sys, $dia) = explode('/', $blood_pressure);
            if (is_numeric($sys) && is_numeric($dia)) {
                $sys = (int)$sys;
                $dia = (int)$dia;
                if ($sys >= 140 || $dia >= 90) {
                    $condition .= ", Hypertension";
                    $recommendations[] = "Your blood pressure is high. Monitor regularly, consult a doctor, and adopt a heart-healthy diet with low sodium. Regular exercise can help manage blood pressure. Visit <a href='meal_plan.php'>Meal Plan</a> for heart-healthy meals and <a href='exercise_plan.php'>Exercise Plan</a> for safe exercises.";
                    $status_class = "status-high";
                } elseif ($sys < 90 || $dia < 60) {
                    $condition .= ", Hypotension";
                    $recommendations[] = "Your blood pressure is low. Consult a doctor to address possible hypotension. A balanced diet and appropriate exercise can support your health. Visit <a href='meal_plan.php'>Meal Plan</a> for tailored nutrition and <a href='exercise_plan.php'>Exercise Plan</a> for suitable activities.";
                    $status_class = "status-low";
                }
            } else {
                $error = "Invalid blood pressure values.";
            }
        } else {
            $error = "Invalid blood pressure format. Use format like 120/80.";
        }

        // Determine overall health status
        $health_status = ($bmi >= 18.5 && $bmi < 25 && $sys >= 90 && $sys <= 120 && $dia >= 60 && $dia <= 80)
            ? "Healthy"
            : "Potentially Ill";

        // Store in session
        $_SESSION["weight"] = $weight;
        $_SESSION["height"] = $height_cm;
        $_SESSION["age"] = $age;
        $_SESSION["blood_pressure"] = $blood_pressure;
        $_SESSION["bmi"] = $bmi;
        $_SESSION["diseases"] = $condition;

        // Update users table
        $user_id = $_SESSION["user_id"];
        $stmt = $conn->prepare("UPDATE users SET weight = ?, height = ?, age = ?, blood_pressure = ?, bmi = ? WHERE id = ?");
        $stmt->bind_param("ddidsi", $weight, $height_cm, $age, $blood_pressure, $bmi, $user_id);
        if (!$stmt->execute()) {
            $error = "Error updating user data: " . $conn->error;
        }
        $stmt->close();

        // Insert into diseases table
        if (!$error && $condition) {
            $stmt = $conn->prepare("INSERT INTO diseases (user_id, health_condition) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $condition);
            if (!$stmt->execute()) {
                $error = "Error saving condition to diseases table: " . $conn->error;
            }
            $stmt->close();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Results</title>
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
        .main {
            max-width: 600px;
            padding: 30px;
            background-color: rgba(200, 230, 201, 0.95);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            text-align: center;
        }
        .result-box {
            background-color: rgba(165, 214, 167, 0.95);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .result-box h2 {
            margin-bottom: 15px;
            color: #27ae60;
        }
        .result-box p {
            margin: 10px 0;
        }
        .status-normal { color: #27ae60; font-weight: bold; }
        .status-high { color: #d32f2f; font-weight: bold; }
        .status-low { color: #ff9800; font-weight: bold; }
        .recommendations {
            margin-top: 20px;
            text-align: left;
        }
        .recommendations ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        .recommendations a {
            color: #27ae60;
            text-decoration: none;
        }
        .recommendations a:hover {
            text-decoration: underline;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #27ae60;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .error {
            color: #d32f2f;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fitness and Diet Planner System</h1>
        <h2>Health Results for <?php echo htmlspecialchars($_SESSION["name"] ?? "User"); ?></h2>
    </div>

    <div class="container">
        <div class="main">
            <div class="result-box">
                <h2>Health Status</h2>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php elseif (isset($health_status)): ?>
                    <p>Weight: <?php echo htmlspecialchars($weight); ?> kg</p>
                    <p>Height: <?php echo htmlspecialchars($height_cm); ?> cm</p>
                    <p>Age: <?php echo htmlspecialchars($age); ?> years</p>
                    <p>Blood Pressure: <?php echo htmlspecialchars($blood_pressure); ?></p>
                    <p>BMI: <?php echo htmlspecialchars($bmi); ?></p>
                    <p>Condition: <span class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($condition); ?></span></p>
                    <p>Health Status: <span class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($health_status); ?></span></p>
                    <?php if (!empty($recommendations)): ?>
                        <div class="recommendations">
                            <h3>Recommendations</h3>
                            <ul>
                                <?php foreach ($recommendations as $rec): ?>
                                    <li><?php echo $rec; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p>No health data submitted yet. Please submit your health information first.</p>
                <?php endif; ?>
                <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>