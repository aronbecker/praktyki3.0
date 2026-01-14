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
    <div class="div" style="margin-bottom: 20px; width: 35%;">
        <p>Wpisz ID firmy jeśli chcesz edytować lub usunąć firmę</p>
        <p>Zostaw wszystkie pozostałe pola puste jeśli chcesz usunąć firmę</p>
    </div>
    
    <div class="div" id='form' style="float: left; margin-right: 50px; padding">
    <h2>Dodaj/Edytuj Firmę</h2>
    <form method="POST" action="addEdit_company.php" style="float: left; margin-left: 20px;">
        <label for="id">ID:</label>
        <input type="number" name="id" id="id" placeholder="ID"><br>
        <label for="company_name">Nazwa Firmy:</label>
        <input type="text" name="nazwa" id="company_name" placeholder="Nazwa Firmy" ><br>
        <label for="street">Ulica:</label>
        <input type="text" name="ulica" id="street" placeholder="Ulica" ><br>
        <label for="postal_code">Kod Pocztowy:</label>
        <input type="text" name="kod_pocztowy" id="postal_code" placeholder="Kod Pocztowy" maxlength="6" ><br>
        <label for="NIP">NIP:</label>
        <input type="number" name="NIP" id="NIP" placeholder="NIP" maxlength="9" ><br>
        <label for="REGON">REGON:</label>
        <input type="number" name="REGON" id="REGON" placeholder="REGON" maxlength="14" ><br>
        <label for="phone">Numer Telefonu:</label>
        <input type="number" name="nr_telefonu" id="phone" placeholder="Numer Telefonu" maxlength="11" ><br>
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" placeholder="Email" ><br>
        <label for="confirm">Potwierdź</label>
        <input type="checkbox" name="" id="confirm" required><br>
        <input type="submit" value="Zatwierdź">
    </form>
    </div>

    <div class="div" id="companyDisplay" style="width: 75%; float: right;">
    <h2>Lista Firm</h2>
    <?php
        $stmt = $conn->prepare("SELECT id, nazwa, ulica, kod_pocztowy, NIP, REGON, nr_telefonu, email FROM firmy");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa</th>
                        <th>Ulica</th>
                        <th>Kod Pocztowy</th>
                        <th>NIP</th>
                        <th>REGON</th>
                        <th>Numer Telefonu</th>
                        <th>Email</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['nazwa']) . "</td>
                        <td>" . htmlspecialchars($row['ulica']) . "</td>
                        <td>" . htmlspecialchars($row['kod_pocztowy']) . "</td>
                        <td>" . htmlspecialchars($row['NIP']) . "</td>
                        <td>" . htmlspecialchars($row['REGON']) . "</td>
                        <td>" . htmlspecialchars($row['nr_telefonu']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "Brak firm w bazie danych.";
        }

        $stmt->close();
        $conn->close();
    ?>
    </div>
    </body>
</html>