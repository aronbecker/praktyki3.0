<?php
include 'dbmanager.php';
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
else {
    $stmt = $conn->prepare("SELECT login FROM users WHERE email = ?");
    $stmt->bind_param("s", $_SESSION['login']);
    $stmt->execute();
    $stmt->bind_result($loginName);
    if ($stmt->fetch()) {
        $displayName = $loginName;
    } else {
        $displayName = $_SESSION['login'];
    }
    echo "Witaj, " . htmlspecialchars($displayName) . "!";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <input type="button" value="Wyloguj się" onclick="window.location.href='logout.php'" id="logoutBtn">
    <div class="Dheader">
    <h1 style="text-align: center;">Skoki</h1>
    </div>
    <div id="divination">
    <input type="text" id="search" placeholder="Szukaj...">
    <input type="button" value="Szukaj" id="searchBtn">
    </div>
    <input type="button" value="Panel administracyjny" id="adminBtn" onclick="window.location.href='admin.php'">
    <div class="companyList" id="companyList">
    
    </div>
</body>
</html>