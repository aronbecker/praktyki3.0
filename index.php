<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';
include_once 'class/users.php';
include_once 'class/kategorie.php';

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
else {
    echo "<div style='padding: 10px; border: none; background-color: white; float: left; width: 10%;'>" . "Witaj, " . htmlspecialchars($_SESSION['login']) . "!" . "</div>";
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
    <input type="button" value="Wyloguj się" onclick="window.location.href='login.php'" id="logoutBtn">
    <div class="Dheader">
    <h1 style="text-align: center;">Skoki</h1>
    </div>
    <div id="divination">
    <form method="GET" action="">
        <label for="sel">Kategoria:</label>
        <select name="kategoria" id="sel" style="margin-right: 10px;" onchange="filterCompanies()">
            <option value="">Wszystkie kategorie</option>
            <?php
            $kategorieObj = new Kategorie();
            $kategorieList = $kategorieObj->getAll();
            foreach ($kategorieList as $kategoria) {
                echo "<option value='" . htmlspecialchars($kategoria['id']) . "'>" . htmlspecialchars($kategoria['nazwa']) . "</option>";
            }
            ?>
        </select>
        <input type="text" id="search" name="search" placeholder="Szukaj..." onkeyup="filterCompanies()">
    </form>
    </div>
    <?php
    $user = new Users();
    if ($user->isAdmin($_SESSION['user_id'])) {
        echo "<input type='button' value='Panel administracyjny' id='adminBtn' onclick=\"window.location.href='admin.php'\">";
    }
    ?>
    <div class="companyList" id="companyList">
    <?php
        $firmy = new Firmy();
        $companiesList = $firmy->getAllWithCategories();
        echo "<table border='1' class='table'>";
        echo "<tr><th>LP</th><th>Kategoria</th><th>Nazwa</th><th>Nazwisko</th><th>Imię</th><th>Telefon</th><th>Email</th><th>Szczegóły</th></tr>";
        foreach ($companiesList as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Lp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategoria']) . "</td>";
            echo "<td>" . htmlspecialchars($row['NazwaPodmiotu']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Nazwisko']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Imie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Telefon']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
            echo "<td><a href='company_details.php?lp=" . urlencode($row['Lp']) . "'>Szczegóły</a></td>";
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
            let companyRows = document.querySelectorAll('.companyList table tr');
            companyRows.forEach((row, index) => {
                if (index === 0) return;
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
    </script>
</body>
</html>