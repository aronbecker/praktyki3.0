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
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <input type="button" value="Powrót do strony głównej" onclick="window.location.href='index.php'" style="margin-bottom: 20px; float: left;">
    <input type="button" value="Zarządzaj użytkownikami" onclick="window.location.href='manage_users.php'" style="margin-bottom: 20px; float: right;">
    <h1>Panel administracyjny</h1>
    <br>
    <input type="button" value="pokaż/ukryj formularz dodawania firmy" id="toggleFormBtn" style="margin-bottom: 20px; float: left;">
    <br><br><br><br>
    <div class="div hidden" id='form' style="float: left; margin-right: 50px; padding">
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
    <div style='width: 96%; float: right; margin-bottom: 20px; margin-right: 10px;' id='searchbox'>
        <form>
        <label for="sel">Kategoria:</label>
        <select name="kategoria" id="sel" style="margin-right: 10px;" onchange="filterCompanies()">
            <option value="">Wszystkie kategorie</option>
            <?php
            $cat_stmt = $conn->prepare("SELECT id, nazwa FROM kategorie ORDER BY nazwa ASC");
            $cat_stmt->execute();
            $cat_result = $cat_stmt->get_result();
            while ($cat_row = $cat_result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($cat_row['id']) . "' " . ">" . htmlspecialchars($cat_row['nazwa']) . "</option>";
            }
            $cat_stmt->close();
            ?>
        </select>
        <br><br>
        <input type="text" id="search" name="search" placeholder="Szukaj..." style='width: 70%; float: left;' onkeyup="filterCompanies()">
        </form>
    </div>
    <div class="div" id="companyList" style="width: 96%; float: right;height: auto;overflow-x: scroll; overflow-y: scroll; max-height: 600px; margin-right: 10px;">
    <h2>Lista Firm</h2>
    <?php
        $sql = "SELECT DISTINCT f.lp, COALESCE(k.nazwa, 'Brak kategorii') AS kategoria, f.nip, f.regon, f.nazwapodmiotu, f.nazwisko, f.imie, f.telefon, f.email, f.adreswww, f.kodpocztowy, f.powiat, f.gmina, f.miejscowosc, f.ulica, f.nrbudynku, f.nrlokalu 
                FROM firmy f 
                LEFT JOIN firma_kategoria fk ON f.lp = fk.firma_lp 
                LEFT JOIN kategorie k ON fk.kategoria_id = k.id 
                ORDER BY f.lp ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<table border='1' class='table'>";
        echo "<tr><th>LP</th><th>Kategoria</th><th>NIP</th><th>REGON</th><th>Nazwa</th><th>Nazwisko</th><th>Imię</th><th>Telefon</th><th>Email</th><th>Adres WWW</th><th>Kod Pocztowy</th><th>Powiat</th><th>Gmina</th><th>Miejscowość</th><th>Ulica</th><th>Numer Budynku</th><th>Numer Lokalu</th><th>Edytuj</th><th>Usuń</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['lp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategoria']) . "</td>";
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
            echo "<td><input type='button' value='Edytuj' class='edit' onclick=\"window.location.href='edit_company.php?lp=" . urlencode($row['lp']) . "'\"></td>";
            echo "<td><input type='button' value='Usuń' class='delete' onclick=\"window.location.href='delete_company.php?lp=" . urlencode($row['lp']) . "'\"></td>";
            echo "</tr>";
        }
        echo "</table>";
        $stmt->close();
        $conn->close();
    ?>
    </div>
    <script>
        function filterCompanies() {
            let searchInput = document.getElementById('search').value.toLowerCase();
            let categorySelect = document.getElementById('sel');
            let selectedCategory = categorySelect.options[categorySelect.selectedIndex].value;
            let companyRows = document.querySelectorAll('#companyList table tr');
            companyRows.forEach((row, index) => {
                if (index === 0) return; // Skip header row
                let cells = row.getElementsByTagName('td');
                let kategoria = cells[1].textContent.toLowerCase();
                let nazwa = cells[4].textContent.toLowerCase();
                let nazwisko = cells[5].textContent.toLowerCase();
                let imie = cells[6].textContent.toLowerCase();
                let matchesSearch = nazwa.includes(searchInput) || nazwisko.includes(searchInput) || imie.includes(searchInput);

                let matchesCategory = true;
                if (selectedCategory == "") {
                    matchesCategory = true;
                } else if (kategoria !== categorySelect.options[categorySelect.selectedIndex].text.toLowerCase()) {
                    matchesCategory = false;
                }
                if (matchesSearch && matchesCategory) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }
        document.getElementById('toggleFormBtn').addEventListener('click', function() {
            let formDiv = document.getElementById('form');
            let searchBox = document.getElementById('searchbox');
            let companyList = document.getElementById('companyList');

            if (formDiv.classList.contains('hidden')) {
                formDiv.classList.remove('hidden');
                searchBox.style.width = '70%';
                companyList.style.width = '70%';
            } else {
                formDiv.classList.add('hidden');
                searchBox.style.width = '96%';
                companyList.style.width = '96%';
            }
        });
    </script>
    </body>
</html>