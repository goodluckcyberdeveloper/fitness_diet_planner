<?php
session_start();

// Hii sehemu unaweza weka validation kama unataka kuruhusu watu waliologin tu
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8" />
    <title>Msaada - Mfumo Wangu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #2c3e50;
        }
        .help-section {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            max-width: 700px;
            margin: auto;
            border-radius: 5px;
        }
        ul {
            line-height: 1.6;
        }
        a {
            color: #2980b9;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="help-section">
    <h1>Msaada kwa Watumiaji</h1>
    <p>Karibu kwenye ukurasa wa msaada wa mfumo wetu. Hapa utapata maelekezo ya jinsi ya kutumia huduma mbalimbali.</p>
    
    <h2>Jinsi ya Kuingia</h2>
    <ul>
        <li>Tumia barua pepe na nenosiri lako kwenye fomu ya <a href="login.php">kuingia</a>.</li>
        <li>Kama hujajiandikisha bado, tembelea ukurasa wa <a href="register.php">usajili</a>.</li>
    </ul>

    <h2>Matatizo ya Kawaida</h2>
    <ul>
        <li><strong>Nimesahau nenosiri langu:</strong> Tumia link ya "Umesahau nenosiri?" kwenye ukurasa wa kuingia.</li>
        <li><strong>Siwezi kufikia baadhi ya sehemu:</strong> Hakikisha umeingia kwenye akaunti yako na una ruhusa ya kutosha.</li>
    </ul>

    <h2>Kuwasiliana nasi</h2>
    <p>Ikiwa unahitaji msaada zaidi, tafadhali wasiliana nasi kupitia barua pepe <a href="mailto:support@example.com">support@example.com</a> au piga simu nambari +255 123 456 789.</p>
</div>

</body>
</html>
