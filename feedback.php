<?php
session_start();
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login_form.html");
    exit();
}

$name = $_SESSION["name"];

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $feedback = htmlspecialchars(trim($_POST['feedback']));

    if (empty($feedback) ) {
        echo "Please supply feedback";
    }

    $user = "SELECT id FROM users WHERE name='$name'";
    $result = $conn->query($user) ;

    if($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $id = $row['id'];

        $insert = "INSERT INTO feedback(user_id , feedback_text) VALUES('$id' , '$feedback')";
        $result = $conn->query($insert);

        if($result) {
            header("Location:dashboard.php");
            $_SESSION['feedback'] = 'feedback';
            exit;
        }
    }

}


?>

<!DOCTYPE html>
<html lang="sw">

<head>
    <meta charset="UTF-8">
    <title>Tuma Maoni</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            padding: 30px;
        }

        form {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: none;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <h2>Tuma Maoni/Feedback</h2>
    <form  method="POST">
        <p>Jina: <strong><?= htmlspecialchars($name) ?></strong></p>
        <textarea name="feedback" placeholder="Andika maoni yako hapa..." ></textarea><br><br>
        <button type="submit">Tuma</button>
    </form>

</body>

</html>