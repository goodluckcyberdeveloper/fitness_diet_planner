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

// Set user data with fallback values
$name = $_SESSION["name"] ?? "User";
$role = $_SESSION["role"] ?? "user";
$type = $_SESSION["type"] ?? "normal";
$diseases = $_SESSION["diseases"] ?? "No information";
$diet_plan = $_SESSION["diet_plan"] ?? "No diet plan";
$exercise_plan = $_SESSION["exercise_plan"] ?? "No exercise plan";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fitness and Diet Planner System</title>
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
            background: linear-gradient(135deg, #27ae60, #219653);
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            font-size: 28px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-bottom: 3px solid #1b5e20;
            animation: fadeIn 1s ease-in;
        }
        .header h1 {
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 20px;
            font-weight: 400;
            opacity: 0.9;
        }
        .header p {
            font-size: 16px;
            margin-top: 5px;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: width 0.3s ease;
        }
        .sidebar.collapsed {
            width: 60px;
            overflow: hidden;
        }
        .menu-toggle {
            display: flex;
            align-items: center;
            background-color: #1b5e20;
            color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .menu-toggle:hover {
            background-color: #2e7d32;
        }
        .menu-toggle span {
            margin-left: 10px;
        }
        .sidebar h3 {
            margin-bottom: 20px;
            font-size: 20px;
            display: none;
        }
        .sidebar.collapsed h3 {
            display: none;
        }
        .menu-items {
            display: block;
        }
        .sidebar.collapsed .menu-items {
            display: none;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .sidebar a:hover {
            background-color: #1b5e20;
            transform: translateX(5px);
        }
        .main {
            flex: 1;
            padding: 30px;
            background-color: rgba(200, 230, 201, 0.95);
            border-radius: 8px;
            margin-left: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            color: #333;
        }
        .info-summary {
            background-color: rgba(165, 214, 167, 0.95);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .info-summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-summary td {
            padding: 10px;
            border-bottom: 1px solid #81c784;
        }
        .info-summary td:first-child {
            font-weight: bold;
            color: #27ae60;
        }
        .info-summary a {
            color: #27ae60;
            text-decoration: none;
        }
        .info-summary a:hover {
            text-decoration: underline;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="header">
        <h1>Fitness and Diet Planner System</h1>
        <h2>👋 Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p>You are a <strong><?php echo htmlspecialchars($role); ?></strong> of type <strong><?php echo htmlspecialchars($type); ?></strong>.</p>
    </div>

    <div class="container">
        <div class="sidebar collapsed">
            <div class="menu-toggle" onclick="toggleMenu()">
                📂 <span class="menu-label">Menu</span>
            </div>
            <h3>📂 Menu</h3>
            <div class="menu-items">
                <a href="health_info.php">🩺 Health Information</a>
                <a href="process_health.php">📋 Health Results</a>
                <a href="meal_plan.php">🥗 Meal Plan</a>
                <a href="exercise_plan.php">🏋️ Exercise Plan</a>
                <a href="dashboard.php">🏠 Dashboard</a>
                <a href="my_progress.php">📊 User Progress</a>
                <a href="record_progress.php">📝 Record Progress</a>
                <a href="save_progress.php">📈 Progress History</a>
                <?php if ($role === 'admin'): ?>
                    <a href="admin_view.php">👑 View Users</a>
                    <a href="view_all_progress.php">📈 Users' Progress</a>
                <?php endif; ?>
                <a href="feedback.php">💬 Feedback</a>
                <a href="logout.php" style="color: red;">🚪 Logout</a>
            </div>
        </div>

        <div class="main">
            <div class="info-summary">
                <h3>🧬 Your Information:</h3>
                <table>
                    <tr><td>First Name</td><td><?php echo htmlspecialchars($name); ?></td></tr>
                    <tr><td>Role</td><td><?php echo htmlspecialchars($role); ?></td></tr>
                    <tr><td>Type</td><td><?php echo htmlspecialchars($type); ?></td></tr>
                    <tr><td>Diseases</td><td><?php echo htmlspecialchars($diseases); ?></td></tr>
                    <tr><td>Diet Plan</td><td><?php echo htmlspecialchars(mb_strimwidth($diet_plan, 0, 60, "...")); ?> <a href="meal_plan.php">[Read more]</a></td></tr>
                    <tr><td>Exercise Plan</td><td><?php echo htmlspecialchars(mb_strimwidth($exercise_plan, 0, 60, "...")); ?> <a href="exercise_plan.php">[Read more]</a></td></tr>
                    <tr><td>Age</td><td><?php echo htmlspecialchars($_SESSION["age"] ?? "N/A"); ?> years</td></tr>
                    <tr><td>Weight</td><td><?php echo htmlspecialchars($_SESSION["weight"] ?? "N/A"); ?> kg</td></tr>
                    <tr><td>Height</td><td><?php echo htmlspecialchars($_SESSION["height"] ?? "N/A"); ?> cm</td></tr>
                    <tr><td>Blood Pressure</td><td><?php echo htmlspecialchars($_SESSION["blood_pressure"] ?? "N/A"); ?></td></tr>
                    <tr><td>BMI</td><td><?php echo htmlspecialchars($_SESSION["bmi"] ?? "N/A"); ?></td></tr>
                </table>
            </div>
        </div>
    </div>

    <script>
    function toggleMenu() {
        const sidebar = document.querySelector('.sidebar');
        const menuLabel = document.querySelector('.menu-label');
        sidebar.classList.toggle('collapsed');
        menuLabel.style.display = sidebar.classList.contains('collapsed') ? 'inline' : 'none';
    }

    document.addEventListener("DOMContentLoaded", function () {
        const userType = "<?php echo htmlspecialchars($_SESSION['type']); ?>";
        const userName = "<?php echo htmlspecialchars($_SESSION['name']); ?>";

        if (userType === "normal") {
            const today = new Date().toISOString().split('T')[0];
            const lastFeedbackDate = localStorage.getItem("lastFeedbackDate");

            if (lastFeedbackDate !== today) {
                setTimeout(function () {
                    Swal.fire({
                        title: '👋 Hello ' + userName + '!',
                        text: 'Have you followed your diet and exercise plan today?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes ✅',
                        cancelButtonText: 'No ❌'
                    }).then((result) => {
                        let feedback = result.isConfirmed ? "Yes" : "No";

                        fetch("save_feedback.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: "feedback=" + encodeURIComponent(feedback)
                        })
                        .then(response => response.text())
                        .then(data => {
                            localStorage.setItem("lastFeedbackDate", today);
                            if (feedback === "Yes") {
                                Swal.fire('Congratulations! 🎉', 'Keep up your great efforts!', 'success');
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'We encourage you 💪',
                                    text: 'Try to follow your plan. You can record your progress now.',
                                    showDenyButton: true,
                                    confirmButtonText: 'Go to record 📋',
                                    denyButtonText: 'Later'
                                }).then((choice) => {
                                    if (choice.isConfirmed) {
                                        window.location.href = "record_progress.php";
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'There is a technical issue. Try again later.', 'error');
                        });
                    });
                }, 1000);
            }
        }
    });
    </script>
</body>
</html>