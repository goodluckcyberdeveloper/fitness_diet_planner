<?php
session_start();
include "config.php"; // Ensure $conn is MySQLi

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Set user data with fallback values
$name = $_SESSION["name"] ?? "User";
$user_id = $_SESSION["user_id"];

// Initialize $stmt to null to avoid undefined variable error
$stmt = null;

// Fetch user health data from database
$health_data = [
    'weight' => null,
    'height' => null,
    'blood_pressure' => null,
    'age' => null,
    'activity_level' => null
];

$stmt = $conn->prepare("SELECT weight, height, blood_pressure, age FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $health_data = $row;
        }
    } else {
        error_log("Failed to execute statement: " . $stmt->error);
    }
    $stmt->close();
} else {
    error_log("Failed to prepare statement: " . $conn->error);
}


// Process health data
$weight = $health_data['weight'] ? (float)$health_data['weight'] : null;
$height = $health_data['height'] ? (float)$health_data['height'] / 100 : null; // Convert cm to meters
$blood_pressure = $health_data['blood_pressure'] ?? null;
$age = $health_data['age'] ? (int)$health_data['age'] : null;
$activity_level = $health_data['activity_level'] ?? 'moderate';

// Calculate BMI if weight and height are available
$bmi = ($weight && $height) ? round($weight / ($height * $height), 1) : null;

// Determine health type based on BMI and blood pressure
$type = "normal";
if ($bmi) {
    if ($bmi < 18.5) {
        $type = "underweight";
    } elseif ($bmi >= 18.5 && $bmi < 25) {
        $type = "normal";
    } elseif ($bmi >= 25 && $bmi < 30) {
        $type = "overweight";
    } elseif ($bmi >= 30) {
        $type = "obesity";
    }
}
if ($blood_pressure) {
    $bp_parts = explode('/', $blood_pressure);
    if (count($bp_parts) == 2) {
        $systolic = (int)$bp_parts[0];
        $diastolic = (int)$bp_parts[1];
        if ($systolic >= 140 || $diastolic >= 90) {
            $type = "hypertension";
        }
    }
}

// Meal plans based on health condition
$mealPlans = [
    "normal" => [
        "advice" => "Eat balanced meals 3 times a day, include fruits, fresh vegetables, plenty of water, and avoid high-fat foods.",
        "weekly_plan" => [
            "Monday" => ["Breakfast: Millet porridge, avocado", "Lunch: Grilled fish, ugali, leafy greens", "Dinner: Rice, beef stew, salad"],
            "Tuesday" => ["Breakfast: Boiled eggs, bread", "Lunch: Chapati, lentils, vegetables", "Dinner: Potatoes, fried chicken, salad"],
            "Wednesday" => ["Breakfast: Sorghum porridge, banana", "Lunch: Ugali, minced beef, kale", "Dinner: Rice, fish, vegetables"],
            "Thursday" => ["Breakfast: Tea, bread, butter", "Lunch: Fried potatoes, chicken, vegetables", "Dinner: Ugali, fish stew, salad"],
            "Friday" => ["Breakfast: Millet porridge, peanuts", "Lunch: Rice, beef, leafy greens", "Dinner: Chapati, lentils, salad"],
            "Saturday" => ["Breakfast: Fried eggs, tea", "Lunch: Ugali, fish, kale", "Dinner: Rice, chicken, vegetables"],
            "Sunday" => ["Breakfast: Sorghum porridge, bread", "Lunch: Potatoes, beef, salad", "Dinner: Chapati, fish stew, vegetables"]
        ]
    ],
    "overweight" => [
        "advice" => "Reduce carbohydrates and sugar. Eat lean proteins like fish, skinless chicken, leafy greens, and avoid sodas/juices.",
        "weekly_plan" => [
            "Monday" => ["Breakfast: Boiled eggs, unsweetened tea", "Lunch: Baked fish, leafy greens, small portion of sweet potatoes", "Dinner: Fruit salad, baked chicken"],
            "Tuesday" => ["Breakfast: Unsweetened sorghum porridge, banana", "Lunch: Baked chicken, leafy greens, small portion of rice", "Dinner: Vegetable soup, baked fish"],
            "Wednesday" => ["Breakfast: Unsweetened tea, few peanuts", "Lunch: Sweet potatoes, vegetables, fish", "Dinner: Salad, boiled chicken"],
            "Thursday" => ["Breakfast: Boiled eggs, tea", "Lunch: Leafy greens, baked fish, small portion of potatoes", "Dinner: Vegetable soup, avocado"],
            "Friday" => ["Breakfast: Millet porridge, banana", "Lunch: Baked chicken, vegetables, small portion of rice", "Dinner: Fruit salad, fish"],
            "Saturday" => ["Breakfast: Unsweetened tea, small bread", "Lunch: Sweet potatoes, vegetables, chicken", "Dinner: Vegetable soup, salad"],
            "Sunday" => ["Breakfast: Fried eggs without oil, tea", "Lunch: Baked fish, leafy greens, small portion of potatoes", "Dinner: Salad, baked chicken"]
        ]
    ],
    "obesity" => [
        "advice" => "Follow a low-calorie diet. Focus on fruits, vegetables, and clean proteins. Avoid fried foods and high-fat foods.",
        "weekly_plan" => [
            "Monday" => ["Breakfast: Unsweetened sorghum porridge, banana", "Lunch: Baked fish, leafy greens, small portion of sweet potatoes", "Dinner: Fruit salad, boiled chicken"],
            "Tuesday" => ["Breakfast: Unsweetened tea, boiled eggs", "Lunch: Baked chicken, vegetables, salad", "Dinner: Vegetable soup, avocado"],
            "Wednesday" => ["Breakfast: Millet porridge, few peanuts", "Lunch: Small portion of sweet potatoes, vegetables, baked fish", "Dinner: Fruit salad, chicken"],
            "Thursday" => ["Breakfast: Tea, banana", "Lunch: Leafy greens, boiled fish, salad", "Dinner: Vegetable soup, boiled eggs"],
            "Friday" => ["Breakfast: Boiled eggs, unsweetened tea", "Lunch: Baked chicken, vegetables, small portion of potatoes", "Dinner: Salad, baked fish"],
            "Saturday" => ["Breakfast: Sorghum porridge, banana", "Lunch: Small portion of sweet potatoes, vegetables, boiled chicken", "Dinner: Vegetable soup, salad"],
            "Sunday" => ["Breakfast: Unsweetened tea, few peanuts", "Lunch: Baked fish, leafy greens", "Dinner: Fruit salad, baked chicken"]
        ]
    ],
    "hypertension" => [
        "advice" => "Reduce salt, sugar, and fats. Consume potassium-rich foods like bananas, spinach, and whole grains.",
        "weekly_plan" => [
            "Monday" => ["Breakfast: Salt-free millet porridge, banana", "Lunch: Baked fish, leafy greens, sweet potatoes", "Dinner: Fruit salad, salt-free chicken"],
            "Tuesday" => ["Breakfast: Unsweetened tea, boiled eggs", "Lunch: Salt-free baked chicken, spinach, whole-grain rice", "Dinner: Salt-free vegetable soup, avocado"],
            "Wednesday" => ["Breakfast: Sorghum porridge, banana", "Lunch: Sweet potatoes, leafy greens, baked fish", "Dinner: Salad, salt-free chicken"],
            "Thursday" => ["Breakfast: Unsweetened tea, whole-grain bread", "Lunch: Leafy greens, boiled fish, salad", "Dinner: Vegetable soup, banana"],
            "Friday" => ["Breakfast: Boiled eggs, unsweetened tea", "Lunch: Salt-free baked chicken, vegetables, small portion of whole-grain rice", "Dinner: Fruit salad, baked fish"],
            "Saturday" => ["Breakfast: Millet porridge, banana", "Lunch: Sweet potatoes, vegetables, salt-free chicken", "Dinner: Salt-free vegetable soup, salad"],
            "Sunday" => ["Breakfast: Unsweetened tea, few peanuts", "Lunch: Baked fish, leafy greens", "Dinner: Fruit salad, salt-free chicken"]
        ]
    ],
    "underweight" => [
        "advice" => "Increase calories and protein in your diet. Eat 5 times a day, including milk, eggs, peanuts, avocado, and carbohydrate-rich foods.",
        "weekly_plan" => [
            "Monday" => ["Breakfast: Millet porridge with milk, peanuts", "Snack: Milk, bread with butter", "Lunch: Rice, minced beef, vegetables, avocado", "Dinner: Chapati, beef stew, boiled eggs"],
            "Tuesday" => ["Breakfast: Fried eggs, bread, milk", "Snack: Banana, peanuts", "Lunch: Ugali, fish, vegetable stew, milk", "Dinner: Potatoes, chicken, salad, milk"],
            "Wednesday" => ["Breakfast: Sorghum porridge with milk, avocado", "Snack: Milk, bread", "Lunch: Rice, beef, leafy greens", "Dinner: Chapati, lentils, eggs"],
            "Thursday" => ["Breakfast: Tea, bread with butter, eggs", "Snack: Peanuts, milk", "Lunch: Potatoes, fried fish, vegetables, avocado", "Dinner: Rice, chicken, salad, milk"],
            "Friday" => ["Breakfast: Millet porridge, peanuts, milk", "Snack: Banana, bread", "Lunch: Ugali, beef, vegetables, milk", "Dinner: Chapati, fish stew, eggs"],
            "Saturday" => ["Breakfast: Fried eggs, milk, bread", "Snack: Avocado, peanuts", "Lunch: Rice, chicken, vegetables, milk", "Dinner: Potatoes, beef, salad, milk"],
            "Sunday" => ["Breakfast: Sorghum porridge, milk, banana", "Snack: Bread, milk", "Lunch: Chapati, fish, vegetables, avocado", "Dinner: Rice, lentils, eggs, milk"]
        ]
    ]
];

// Select meal plan
$selected_plan = $mealPlans[$type] ?? $mealPlans["normal"];
$advice = $selected_plan['advice'];
$weekly_plan = $selected_plan['weekly_plan'];

// Personalized recommendations
$personalized_advice = $advice;
if ($bmi) {
    $personalized_advice .= " Your BMI is $bmi. ";
    if ($type === "underweight") {
        $personalized_advice .= "Try to gain weight by eating calorie- and protein-rich foods.";
    } elseif ($type === "overweight" || $type === "obesity") {
        $personalized_advice .= "Reduce weight by eating low-calorie foods and exercising regularly.";
        if ($activity_level === 'sedentary') {
            $personalized_advice .= " Increase physical activity, such as walking for 30 minutes daily.";
        }
    }
}
if ($blood_pressure && $type === "hypertension") {
    $personalized_advice .= " Monitor your blood pressure. Avoid stress and salt in your diet.";
}
if ($age && $age > 50) {
    $personalized_advice .= " Eat foods rich in calcium and vitamin D to strengthen bones.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal Plan - <?php echo htmlspecialchars($name); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('image3.jpg'); /* Replace with the correct image name (.jpg, .png, etc.) */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }
        .container {
            width: 100%;
            max-width: 900px;
            background-color: rgba(255, 255, 255, 0.95); /* Semi-transparent white */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
        }
        h1, h2, h3 {
            color: #27ae60; /* Green for consistency */
        }
        .meal-plan {
            background: rgba(230, 255, 237, 0.95); /* Semi-transparent light green */
            padding: 20px;
            border-left: 6px solid #27ae60; /* Green border */
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #27ae60; /* Green border */
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #27ae60; /* Green */
            color: white;
        }
        tr:nth-child(even) {
            background-color: rgba(249, 249, 249, 0.95); /* Semi-transparent light gray */
        }
        .health-info {
            font-style: italic;
            color: #555;
        }
        .error-message {
            color: #d9534f;
            font-weight: bold;
            margin-bottom: 20px;
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
        <h1>Meal Plan for <?php echo htmlspecialchars($name); ?></h1>
        
        <?php if ($bmi || $blood_pressure || $age ) : ?>
            <h3 class="health-info">Health Information:</h3>
            <ul style="text-align: left;">
                <?php if ($bmi) : ?>
                    <li><strong>BMI:</strong> <?php echo $bmi; ?> (<?php echo htmlspecialchars($type); ?>)</li>
                <?php endif; ?>
                <?php if ($weight) : ?>
                    <li><strong>Weight:</strong> <?php echo $weight; ?> kg</li>
                <?php endif; ?>
                <?php if ($height) : ?>
                    <li><strong>Height:</strong> <?php echo $height * 100; ?> cm</li>
                <?php endif; ?>
                <?php if ($blood_pressure) : ?>
                    <li><strong>Blood Pressure:</strong> <?php echo htmlspecialchars($blood_pressure); ?> mmHg</li>
                <?php endif; ?>
                <?php if ($age) : ?>
                    <li><strong>Age:</strong> <?php echo $age; ?> years</li>
                <?php endif; ?>
                <?php if ($activity_level) : ?>
                    <li><strong>Activity Level:</strong> <?php echo htmlspecialchars($activity_level); ?></li>
                <?php endif; ?>
            </ul>

            <h2>Health Advice</h2>
        <div class="meal-plan">
            <?php echo htmlspecialchars($personalized_advice); ?>
        </div>
        <h2>Weekly Meal Schedule</h2>
        <table>
            <tr>
                <th>Day</th>
                <th>Meal</th>
            </tr>
            <?php foreach ($weekly_plan as $day => $meals) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($day); ?></td>
                    <td>
                        <ul>
                            <?php foreach ($meals as $meal) : ?>
                                <li><?php echo htmlspecialchars($meal); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <div class="error-message">You do not have any details . Please update your health Information</div>
        <?php endif ?>

        
        <a href="dashboard.php" class="back-button">Back</a>
    </div>
</body>
</html>