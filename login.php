<?php
ob_start(); // Start output buffering to prevent header errors
session_start();
include "config.php";

// Initialize error variable
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST["email"] ?? "")); // Convert to lowercase for case-insensitive comparison
    $password = trim($_POST["password"] ?? "");

    // Server-side validation
    if (empty($email)) {
        $error = "❌ Email is required!";
    } elseif (empty($password)) {
        $error = "❌ Password is required!";
    } else {
        // Debug: Log the email being searched
        error_log("Searching for email: " . $email);

        // Use prepared statement for security
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            $error = "❌ Failed to prepare statement: " . $conn->error;
            error_log("Prepare failed: " . $conn->error);
        } else {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $error = "❌ Execution failed: " . $stmt->error;
                error_log("Execute failed: " . $stmt->error);
            } else {
                $result = $stmt->get_result();

                // Debug: Log the number of rows found
                error_log("Rows found: " . $result->num_rows);

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();

                    // Debug: Log user data
                    error_log("User found: " . print_r($user, true));

                    if (password_verify($password, $user["password"])) {
                        // Store session
                        $_SESSION["user_id"] = $user["id"];
                        $_SESSION["name"] = $user["name"];
                        $_SESSION["role"] = $user["role"];
                        $_SESSION["type"] = $user["type"];
                        $_SESSION["diseases"] = $user["diseases"];
                        $_SESSION["diet_plan"] = $user["diet_plan"];
                        $_SESSION["exercise_plan"] = $user["exercise_plan"];

                        // Redirect based on role
                        if ($_SESSION["role"] === "admin") {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: dashboard.php");
                        }
                        exit();
                    } else {
                        $error = "⚠️ Password is incorrect!";
                        error_log("Password mismatch for email: " . $email);
                    }
                } else {
                    $error = "❌ Email does not exist in the system!";
                    error_log("No user found for email: " . $email);
                }

                $stmt->close();
            }
        }
    }
    $conn->close();
    ob_end_flush(); // End buffering and flush output
}

// Redirect if already logged in
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] === "admin") {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - Fitness and Diet Planner System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('image2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #fff;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            color: #333;
        }

        .login-container h2 {
            text-align: center;
            color: #27ae60;
            margin-bottom: 25px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #27ae60;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #219653;
        }

        .footer-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .footer-link a {
            color: #27ae60;
            text-decoration: none;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>🔐 Log In to Fitness and Diet Planner System</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST" autocomplete="off">
        <label for="email">📧 Email:</label>
        <input type="email" id="email" name="email" required 
               autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">

        <label for="password">🔑 Password:</label>
        <input type="password" id="password" name="password" required 
               autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');">

        <button type="submit">Log In</button>
    </form>
    <div class="footer-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>
</body>
</html>