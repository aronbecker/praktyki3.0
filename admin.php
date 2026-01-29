<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';
include_once 'class/kategorie.php';
include_once 'class/users.php';

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$user = new Users();
if (!$user->isAdmin($_SESSION['user_id'])) {
    header("Location: index.php");
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
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            width: 96%;
            margin-left: auto;
            margin-right: auto;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <?php
    // Wyświetlanie komunikatów
    if (isset($_SESSION['success'])) {
        echo "<div class='alert alert-success'>";
        echo htmlspecialchars($_SESSION['success']);
        echo "</div>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-error'>";
        echo htmlspecialchars($_SESSION['error']);
        echo "</div>";
        unset($_SESSION['error']);
    }
    ?>
    <input type="button" value="Powrót do strony głównej" onclick="window.location.href='index.php'" style="margin-bottom: 20px; float: left;">
    <input type="button" value="Zarządzaj użytkownikami" onclick="window.location.href='manage_users.php'" style="margin-bottom: 20px; float: right;">
    <h1>Panel administracyjny</h1>
    <br>
    <input type="button" value="pokaż/ukryj formularz dodawania firmy" id="toggleFormBtn" style="margin-bottom: 20px; margin-right: 20px; float: left;">
    <input type="button" value="importuj" id="import" style="float:left;" onclick="document.getElementById('importModal').style.display='block'">
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
        <label for="adreswww">Adres WWW:</label><br>
        <input type="text" id="adreswww" name="adreswww"><br>
        <label for="kodpocztowy">Kod Pocztowy:</label><br>
        <input type="text" id="kodpocztowy" name="kodpocztowy" ><br>
        <label for="powiat">Powiat:</label><br>
        <input type="text" id="powiat" name="powiat" ><br>
        <label for="gmina">Gmina:</label><br>
        <input type="text" id="gmina" name="gmina" ><br>
        <label for="miejscowosc">Miejscowość:</label><br>
        <input type="text" id="miejscowosc" name="miejscowosc" ><br>
        <label for="ulica">Ulica:</label><br>
        <input type="text" id="ulica" name="ulica" ><br>
        <label for="nrbudynku">Numer Budynku:</label><br>
        <input type="text" id="nrbudynku" name="nrbudynku" ><br>
        <label for="nrlokalu">Numer Lokalu:</label><br>
        <input type="text" id="nrlokalu" name="nrlokalu"><br><br>
        <input type="submit" value="Zatwierdź">
    </form>
    </div>
    <div style='width: 96%; float: right; margin-bottom: 20px; margin-right: 10px;' id='searchbox'>
        <form>
        <label for="sel">Kategoria:</label>
        <select name="kategoria" id="sel" style="margin-right: 10px;" onchange="filterCompanies()">
            <option value="">Wszystkie kategorie</option>
            <?php
            $kategorieObj = new Kategorie();
            $kategorieList = $kategorieObj->getAll();
            foreach ($kategorieList as $kat) {
                echo "<option value='" . htmlspecialchars($kat['id']) . "'>" . htmlspecialchars($kat['nazwa']) . "</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="text" id="search" name="search" placeholder="Szukaj..." style='width: 70%; float: left;' onkeyup="filterCompanies()">
        </form>
    </div>
    <!-- Modal do importu CSV -->
    <div id="importModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('importModal').style.display='none'">&times;</span>
            <h2>Importuj dane z pliku CSV</h2>
            <form id="importForm" action="import_csv.php" method="POST" enctype="multipart/form-data">
                <input type="file" id="csvFile" name="csvFile" accept=".csv" required style="margin: 10px 0; display: block;">
                <br>
                <input type="submit" value="Importuj">
                <input type="button" value="Anuluj" onclick="document.getElementById('importModal').style.display='none'">
            </form>
        </div>
    </div>

    <div class="div" id="companyList" style="width: 96%; float: right;height: auto;overflow-x: scroll; overflow-y: scroll; max-height: 600px; margin-right: 10px;">
    <h2>Lista Firm</h2>
    <?php
        $firmy = new Firmy();
        $firmesList = $firmy->getAllWithCategories();
        echo "<table border='1' class='table'>";
        echo "<tr><th>LP</th><th>Kategoria</th><th>Nazwa</th><th>Nazwisko</th><th>Imię</th><th>Telefon</th><th>Email</th><th>Adres WWW</th><th>Kod Pocztowy</th><th>Powiat</th><th>Gmina</th><th>Miejscowość</th><th>Ulica</th><th>Numer Budynku</th><th>Numer Lokalu</th><th>Edytuj</th><th>Usuń</th></tr>";
        foreach ($firmesList as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Lp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategoria']) . "</td>";
            echo "<td>" . htmlspecialchars($row['NazwaPodmiotu']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Nazwisko']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Imie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Telefon']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['AdresWWW']) . "</td>";
            echo "<td>" . htmlspecialchars($row['KodPocztowy']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Powiat']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Gmina']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Miejscowosc']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Ulica']) . "</td>";
            echo "<td>" . htmlspecialchars($row['NrBudynku']) . "</td>";
            echo "<td>" . htmlspecialchars($row['NrLokalu']) . "</td>";
            echo "<td><input type='button' value='Edytuj' class='edit' onclick=\"window.location.href='edit_company.php?lp=" . urlencode($row['Lp']) . "'\"></td>";
            echo "<td><input type='button' value='Usuń' class='delete' onclick=\"window.location.href='delete_company.php?lp=" . urlencode($row['Lp']) . "'\"></td>";
            echo "</tr>";
        }
        echo "</table>";
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
                let nazwa = cells[2].textContent.toLowerCase();
                let nazwisko = cells[3].textContent.toLowerCase();
                let imie = cells[4].textContent.toLowerCase();
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