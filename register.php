<?php
ob_start(); // Start output buffering to prevent header errors
session_start();
include 'db_connection.php';

// Initialize variables
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim(strtolower($_POST['email'] ?? '')); // Case-insensitive email
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $role = trim($_POST['role'] ?? 'user'); // Default to 'user', allow 'normal' or 'patient'

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Invalid email format!";
    } elseif (empty($name) || empty($password) || empty($confirm_password) || empty($address)) {
        $error = "❌ All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "❌ Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "❌ Password must be at least 6 characters!";
    } else {
        // Hash password only after validation
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt === false) {
            $error = "❌ Prepare failed: " . $conn->error;
            error_log("Prepare failed: " . $conn->error);
        } else {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $error = "❌ Execute failed: " . $stmt->error;
                error_log("Execute failed: " . $stmt->error);
            } else {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $error = "❌ Email already registered!";
                } else {
                    $stmt->close();
                    $id = 2;

                    // Proceed with registration
                    $stmt = $conn->prepare("INSERT INTO users ( name, email, password, address, role) VALUES (?, ?, ?, ?, ?)");
                    if ($stmt === false) {
                        $error = "❌ Prepare insert failed: " . $conn->error;
                        error_log("Prepare insert failed: " . $conn->error);
                    } else {
                        $stmt->bind_param("sssss", $name, $email, $password_hashed, $address, $role);
                        if (!$stmt->execute()) {
                            $error = "❌ Insert failed: " . $stmt->error;
                            error_log("Insert failed: " . $stmt->error . " | Data: name=$name, email=$email, role=$role");
                        } else {
                            // Get the last inserted ID
                            $user_id = $conn->insert_id;

                            // Store session for the new user
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["name"] = $name;
                            $_SESSION["role"] = $role;
                            $_SESSION["type"] = $role; // Assuming type is same as role for now

                            // Redirect to dashboard
                            if ($role === "admin") {
                                header("Location: admin_dashboard.php");
                                exit();
                            } else {
                                header("Location: dashboard.php"); // Changed from login.php to dashboard.php
                                exit();
                            }
                        }
                        $stmt->close();
                    }
                }
            }
        }
    }
    $conn->close();
    ob_end_flush(); // End buffering and flush output

    // If there's an error, display it
    if (!empty($error)) {
        echo '<div class="error">' . $error . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Green</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('image2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 20px;
            width: 500px;
            box-shadow: 0 5px 8px rgba(0,0,0,0.1);
            color: #333;
            font-size: 16px;
        }
        h1 {
            text-align: center;
            color: #27ae60;
            font-size: 1.5rem;
            margin: 10px 0;
        }
        p.subtitle {
            text-align: center;
            font-size: 14px;
            color: #27ae60;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .password-container {
            position: relative;
            width: 100%;
            margin-bottom: 10px;
        }
        .password-container input {
            width: 100%;
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 14px;
            color: #27ae60;
        }
        button {
            width: 100%;
            background-color: #27ae60;
            color: white;
            font-size: 16px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #219653;
        }
        .login-link, .home-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }
        .login-link a, .home-link a {
            color: #27ae60;
            font-weight: 600;
            text-decoration: none;
        }
        .login-link a:hover, .home-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Fitness and diet planner</h1>
        <p class="subtitle">Join us for a healthier lifestyle</p>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="name" placeholder="Full Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="password" required minlength="6" autocomplete="new-password" />
                <span class="toggle-password" onclick="togglePassword('password')">Show</span>
            </div>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required minlength="6" autocomplete="new-password" />
                <span class="toggle-password" onclick="togglePassword('confirm_password')">Show</span>
            </div>
            <input type="text" name="address" placeholder="Address (street, city)" required />
            <select name="role">
                <option value="normal">Normal</option>
                <option value="patient">Patient</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Log in here</a></p>
        <p class="home-link"><a href="homepage.php">Return to Homepage</a></p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const toggle = input.nextElementSibling;
            if (input.type === "password") {
                input.type = "text";
                toggle.textContent = "Hide";
            } else {
                input.type = "password";
                toggle.textContent = "Show";
            }
        }
    </script>
</body>
</html>