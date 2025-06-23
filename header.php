<?php
session_start();
$user_role = $_SESSION["role"] ?? "user"; // au "admin"
?>

<style>
/* Style ya header na menu */
header {
    background-color: #1abc9c;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 30px;
    font-family: Arial, sans-serif;
}

.header-left img {
    height: 50px;
    border-radius: 8px;
}

.nav-links {
    display: flex;
    gap: 25px;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
}

.nav-links a:hover {
    text-decoration: underline;
}
</style>

<header>
    <div class="header-left">
        <img src="images/logo.png" alt="Logo" />
    </div>

    <nav class="nav-links">
        <a href="dashboard.php">Home</a>
        <a href="help.php">Help</a>
        <a href="about.php">About</a>
    </nav>
</header>
