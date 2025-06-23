<?php
session_start();
include "config.php";

// Lazima awe ame-login
if (!isset($_SESSION["user_id"])) {
    header("Location: login_form.html");
    exit();
}

// Kama fomu imetumwa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $notes = $_POST["notes"];

    $stmt = $conn->prepare("INSERT INTO user_progress (user_id, notes) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $notes);

    if ($stmt->execute()) {
        echo "✅ Maendeleo yako yamehifadhiwa vizuri!";
    } else {
        echo "⚠️ Kuna tatizo: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Rekodi Maendeleo</title>
</head>
<body>

<h2>Rekodi Maendeleo Yangu</h2>

<form method="POST" action="">
    <label for="notes">Eleza maendeleo yako ya leo:</label><br>
    <textarea name="notes" rows="5" cols="40" required></textarea><br><br>
    <button type="submit">💾 Hifadhi</button>
</form>

<br><a href="dashboard.php">⬅️ Rudi Dashboard</a>

</body>
</html>
