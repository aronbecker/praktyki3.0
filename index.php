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
    echo "<div style='padding: 10px; border: 2px solid black; background-color: white; float: left; width: 10%;'>" . "Witaj, " . htmlspecialchars($displayName) . "!" . "</div>";
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
            $cat_stmt = $conn->prepare("SELECT id, nazwa FROM kategorie ORDER BY nazwa ASC");
            $cat_stmt->execute();
            $cat_result = $cat_stmt->get_result();
            while ($cat_row = $cat_result->fetch_assoc()) {
                $selected = (isset($_GET['kategoria']) && $_GET['kategoria'] == $cat_row['id']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($cat_row['id']) . "' " . $selected . ">" . htmlspecialchars($cat_row['nazwa']) . "</option>";
            }
            $cat_stmt->close();
            ?>
        </select>
        <br><br>
        <input type="text" id="search" name="search" placeholder="Szukaj..." onkeyup="filterCompanies()">
    </form>
    </div>
    <?php
    if ($_SESSION['admin'] == 1) {
        echo "<input type='button' value='Panel administracyjny' id='adminBtn' onclick=\"window.location.href='admin.php'\">";
    }
    ?>
    <div class="companyList" id="companyList">
    <?php
        include 'dbmanager.php';
        $stmt = $conn->prepare("
            SELECT f.lp, f.nazwapodmiotu, f.nazwisko, f.imie, f.telefon, f.email, COALESCE(k.nazwa, 'Brak kategorii') AS kategoria
            FROM firmy f
            LEFT JOIN firma_kategoria fk ON f.lp = fk.firma_lp
            LEFT JOIN kategorie k ON fk.kategoria_id = k.id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<table border='1' class='table'>";
        echo "<tr><th>LP</th><th>Kategoria</th><th>Nazwa</th><th>Nazwisko</th><th>Imię</th><th>Telefon</th><th>Email</th><th>Szczegóły</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['lp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategoria']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nazwapodmiotu']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nazwisko']) . "</td>";
            echo "<td>" . htmlspecialchars($row['imie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telefon']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td><a href='company_details.php?lp=" . urlencode($row['lp']) . "'>Szczegóły</a></td>";
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