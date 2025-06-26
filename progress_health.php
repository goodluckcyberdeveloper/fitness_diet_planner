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
$user_id = $_SESSION["user_id"];
$weight = null;
$height_cm = null;
$age = null;
$blood_pressure = null;
$date = null;
$bmi = null;
$condition = null;
$error = null;
$recommendations = [];
$status_class = "status-normal";
$health_status = null;

// Handle form submission from health_info.php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["weight"], $_POST["height"], $_POST["age"], $_POST["blood_pressure"], $_POST["date"])) {
    $weight = floatval($_POST["weight"]);
    $height_cm = floatval($_POST["height"]);
    $age = intval($_POST["age"]);
    $blood_pressure = $_POST["blood_pressure"];
    $date = $_POST["date"];

    // Validate inputs
    if ($weight < 20 || $weight > 300) {
        $error = "Invalid weight. Must be between 20 and 300 kg.";
    } elseif ($height_cm < 50 || $height_cm > 300) {
        $error = "Invalid height. Must be between 50 and 300 cm.";
    } elseif ($age < 10 || $age > 120) {
        $error = "Invalid age. Must be between 10 and 120 years.";
    } elseif (!preg_match("/^\d{2,3}\/\d{2,3}$/", $blood_pressure)) {
        $error = "Invalid blood pressure format. Use format like 120/80.";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
        $error = "Invalid date format. Please select a valid date.";
    } else {
        // Calculate BMI
        $height_m = $height_cm / 100;
        $bmi = round($weight / ($height_m * $height_m), 1);

        // Determine BMI condition
        if ($bmi < 18.5) {
            $condition = "Underweight";
            $recommendations[] = "Your BMI is low, indicating underweight. To gain weight healthily, follow a nutrient-rich meal plan and engage in strength-building exercises. Visit <a href='meal_plan.php'>Mpango wa Chakula</a> for a tailored diet and <a href='exercise_plan.php'>Mpango wa Mazoezi</a> for suitable workouts.";
            $status_class = "status-low";
        } elseif ($bmi < 25) {
            $condition = "Normal";
            $recommendations[] = "Your BMI is normal. Maintain your health with a balanced diet and regular exercise. Visit <a href='meal_plan.php'>Mpango wa Chakula</a> for personalized meal ideas and <a href='exercise_plan.php'>Mpango wa Mazoezi</a> for fitness routines to stay healthy.";
            $status_class = "status-normal";
        } elseif ($bmi < 30) {
            $condition = "Overweight";
            $recommendations[] = "Your BMI is high, indicating overweight. Adopt a balanced diet and increase physical activity to manage weight. Visit <a href='meal_plan.php'>Mpango wa Chakula</a> for a tailored diet plan and <a href='exercise_plan.php'>Mpango wa Mazoezi</a> for effective exercises.";
            $status_class = "status-high";
        } else {
            $condition = "Obesity";
            $recommendations[] = "Your BMI is very high, indicating obesity. Consult a healthcare provider and follow a structured diet and exercise plan to manage weight. Visit <a href='meal_plan.php'>Mpango wa Chakula</a> for a personalized diet and <a href='exercise_plan.php'>Mpango wa Mazoezi</a> for safe workouts.";
            $status_class = "status-high";
        }

        // Parse and evaluate blood pressure
        if (strpos($blood_pressure, '/') !== false) {
            list($sys, $dia) = explode('/', $blood_pressure);
            if (is_numeric($sys) && is_numeric($dia)) {
                $sys = (int)$sys;
                $dia = (int)$dia;
                if ($sys >= 140 || $dia >= 90) {
                    $condition .= ": Hypertension";
                    $recommendations[] = "Your blood pressure is high. Monitor regularly, consult a doctor, and adopt a heart-healthy diet with low sodium. Regular exercise can help manage blood pressure. Visit <a href='meal_plan.php'>Mpango wa Chakula</a> for heart-healthy meals and <a href='exercise_plan.php'>Mpango wa Mazoezi</a> for safe exercises.";
                    $status_class = "status-high";
                } elseif ($sys < 90 || $dia < 60) {
                    $condition .= ": Hypotension";
                    $recommendations[] = "Your blood pressure is low. Consult a doctor to address possible symptoms. A balanced diet and appropriate exercise can help. Visit <a href='meal_plan.php'>Mpango wa Chakula</a> for tailored nutrition and <a href='exercise_plan.php'>Mpango wa Mazoezi</a> for suitable activities.";
                    $status_class = "status-low";
                }
            } else {
                $error = "Invalid blood pressure values.";
            }
        } else {
            $error = "Invalid blood pressure format.";
        }

        // Determine overall health status
        $health_status = ($bmi >= 18.5 && $bmi < 25 && $sys >= 90 && $sys <= 120 && $dia >= 60 && $dia <= 80)
            ? "Healthy"
            : "Potentially Ill";

        // Store in session for later use
        $_SESSION["weight"] = $weight;
        $_SESSION["height"] = $height_cm;
        $_SESSION["age"] = $age;
        $_SESSION["blood_pressure"] = $blood_pressure;
        $_SESSION["date"] = $date;
        $_SESSION["bmi"] = $bmi;
        $_SESSION["diseases"] = $condition;
    }
} else {
    $error = "No health data submitted. Please fill out the health information form.";
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pitia Taarifa za Afya</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .header h1 {
            margin-bottom: 10px;
        }
        .container {
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: flex-start;
            margin: 30px auto;
            width: 100%;
            max-width: 1200px;
            padding: 0 15px;
        }
        .form-box {
            background: rgba(255, 255, 255, 0.97);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            text-align: left;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
        }
        p {
            color: #2c3e50;
            margin: 12px 0;
            font-size: 16px;
            line-height: 1.5;
        }
        .status-normal {
            color: #27ae60;
            font-weight: bold;
        }
        .status-high {
            color: #c0392b;
            font-weight: bold;
        }
        .status-low {
            color: #e67e22;
            font-weight: bold;
        }
        .recommendations {
            margin-top: 25px;
            color: #2c3e50;
        }
        .recommendations h3 {
            color: #27ae60;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 600;
        }
        .recommendations ul {
            list-style-type: disc;
            margin-left: 25px;
            font-size: 16px;
            line-height: 1.7;
            overflow-wrap: break-word;
        }
        .recommendations li {
            margin-bottom: 12px;
        }
        .recommendations a {
            color: #27ae60;
            text-decoration: none;
            font-weight: 500;
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
            font-weight: 500;
            font-size: 16px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .error {
            color: #c0392b;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
        }
        @media (max-width: 600px) {
            .form-box {
                padding: 25px;
                max-width: 90%;
            }
            h2 {
                font-size: 20px;
            }
            p, .recommendations ul, .back-link {
                font-size: 14px;
            }
            .recommendations h3 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mpangaji wa Mazoezi na Lishe</h1>
        <h2>Taarifa za Afya za <?php echo htmlspecialchars($_SESSION["name"] ?? "Mtumiaji"); ?></h2>
    </div>

    <div class="container">
        <div class="form-box">
            <h2>Pitia Taarifa Zako za Afya</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <a class="back-link" href="health_info.php">← Rudi kwenye Taarifa za Afya</a>
            <?php elseif (isset($health_status)): ?>
                <p><strong>Uzito:</strong> <?php echo htmlspecialchars($weight); ?> kg</p>
                <p><strong>Urefu:</strong> <?php echo htmlspecialchars($height_cm); ?> cm</p>
                <p><strong>Umri:</strong> <?php echo htmlspecialchars($age); ?> miaka</p>
                <p><strong>Shinikizo la Damu:</strong> <?php echo htmlspecialchars($blood_pressure); ?></p>
                <p><strong>Tarehe:</strong> <?php echo htmlspecialchars($date); ?></p>
                <p><strong>BMI:</strong> <?php echo htmlspecialchars($bmi); ?></p>
                <p><strong>Hali:</strong> <span class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($condition); ?></span></p>
                <p><strong>Hali ya Afya:</strong> <span class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($health_status); ?></span></p>
                <?php if (!empty($recommendations)): ?>
                    <div class="recommendations">
                        <h3>Mapendekezo</h3>
                        <ul>
                            <?php foreach ($recommendations as $rec): ?>
                                <li><?php echo $rec; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <a class="back-link" href="dashboard.php">← Rudi kwenye Dashibodi</a>
            <?php else: ?>
                <p>Hakuna data ya afya iliyowasilishwa bado. Tafadhali jaza fomu ya taarifa za afya kwanza.</p>
                <a class="back-link" href="health_info.php">← Rudi kwenye Taarifa za Afya</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>