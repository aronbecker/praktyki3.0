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
    $stmt->close();
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
    <?php
        $stmt = $conn->prepare("SELECT lp, nip, regon, nazwapodmiotu, nazwisko, imie, telefon, email, adreswww, kodpocztowy, powiat, gmina, miejscowosc, ulica, nrbudynku, nrlokalu FROM company");
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<table border='1'>";
        echo "<tr><th>LP</th><th>NIP</th><th>REGON</th><th>Nazwa</th><th>Nazwisko</th><th>Imię</th><th>Telefon</th><th>Email</th><th>Adres WWW</th><th>Kod Pocztowy</th><th>Powiat</th><th>Gmina</th><th>Miejscowość</th><th>Ulica</th><th>Numer Budynku</th><th>Numer Lokalu</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['lp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
            echo "<td>" . htmlspecialchars($row['regon']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nazwapodmiotu']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nazwisko']) . "</td>";
            echo "<td>" . htmlspecialchars($row['imie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telefon']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['adreswww']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kodpocztowy']) . "</td>";
            echo "<td>" . htmlspecialchars($row['powiat']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gmina']) . "</td>";
            echo "<td>" . htmlspecialchars($row['miejscowosc']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ulica']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nrbudynku']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nrlokalu']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        $stmt->close();
        $conn->close();
    ?>
    </div>
</body>
</html>