<?php
    include 'dbmanager.php';
    session_start();
    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style2.css">
    <style>
        input{
            margin-left:auto;
            margin-right:auto;
            display: block;
        }
    </style>
</head>
<body>
    <h1>Panel administracyjny</h1>
    <input type="button" value="Powrót do strony głównej" onclick="window.location.href='index.php'" style="margin-bottom: 20px; float: left;">
    <br><br><br><br>
    <div class="div" id='form' style="float: left; margin-right: 50px; padding">
    <h2>Dodaj Firmę</h2>
    <form method="POST" action="add_company.php" style="float: left; margin-left: 20px;">
        <label for="NIP">NIP:</label><br>
        <input type="text" id="NIP" name="NIP" ><br>
        <label for="REGON">REGON:</label><br>
        <input type="text" id="REGON" name="REGON" ><br>
        <label for="nazwa">Nazwa:</label><br>
        <input type="text" id="nazwa" name="nazwa" required><br>
        <label for="imie">Imię:</label>
        <input type="text" id="imie" name="imie" ><br>
        <label for="nazwisko">Nazwisko:</label>
        <input type="text" id="nazwisko" name="nazwisko" ><br>
        <label for="nr_telefonu">Numer Telefonu:</label><br>
        <input type="text" id="nr_telefonu" name="nr_telefonu" ><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" ><br>
        <label for="adres_www">Adres WWW:</label><br>
        <input type="text" id="adres_www" name="adres_www"><br>
        <label for="kod_pocztowy">Kod Pocztowy:</label><br>
        <input type="text" id="kod_pocztowy" name="kod_pocztowy" ><br>
        <label for="powiat">Powiat:</label><br>
        <input type="text" id="powiat" name="powiat" ><br>
        <label for="gmina">Gmina:</label><br>
        <input type="text" id="gmina" name="gmina" ><br>
        <label for="miejscowosc">Miejscowość:</label><br>
        <input type="text" id="miejscowosc" name="miejscowosc" ><br>
        <label for="ulica">Ulica:</label><br>
        <input type="text" id="ulica" name="ulica" ><br>
        <label for="numer_budynku">Numer Budynku:</label><br>
        <input type="text" id="numer_budynku" name="numer_budynku" ><br>
        <label for="numer_lokalu">Numer Lokalu:</label><br>
        <input type="text" id="numer_lokalu" name="numer_lokalu"><br><br>
        <input type="submit" value="Zatwierdź">
    </form>
    </div>

    <div class="div" id="companyDisplay" style="width: 75%; float: right;overflow-y: scroll;height: 600px;overflow-x: scroll;">
    <h2>Lista Firm</h2>
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
            echo "<td><a href='edit_company.php?lp=" . urlencode($row['lp']) . "'>Edytuj</a></td>";
            echo "<td><a href='delete_company.php?lp=" . urlencode($row['lp']) . "' onclick=\"return confirm('Czy na pewno chcesz usunąć tę firmę?');\">Usuń</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        $stmt->close();
        $conn->close();
    ?>
    </div>
    </body>
</html>