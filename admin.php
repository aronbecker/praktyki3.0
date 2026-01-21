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
    <div style='width: 98%; float: right; margin-bottom: 20px;' id='searchbox'>
        <form method="GET" action="">
        <label for="sel">Kategoria:</label>
        <select name="kategoria" id="sel" style="margin-right: 10px;">
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
        <input type="text" id="search" name="search" placeholder="Szukaj..." style='width: 70%; float: left;'>
        <input type="submit" value="Szukaj" id="searchBtn" style="float: right;">
        </form>
    </div>
    <div class="div" id="companyList" style="width: 98%; float: right;height: auto;overflow-x: scroll;">
    <h2>Lista Firm</h2>
    <?php
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $kategoria = isset($_GET['kategoria']) ? (int)$_GET['kategoria'] : 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $per_page = 25;
        $offset = ($page - 1) * $per_page;
        
        $count_sql = "SELECT COUNT(DISTINCT firmy.lp) as total FROM firmy";
        if ($kategoria > 0) {
            $count_sql .= " JOIN firma_kategoria fk ON firmy.lp = fk.firma_lp WHERE fk.kategoria_id = ?";
            if (!empty($searchQuery)) {
                $count_sql .= " AND (nazwapodmiotu LIKE ? OR nip LIKE ? OR email LIKE ?)";
            }
        } elseif (!empty($searchQuery)) {
            $count_sql .= " WHERE nazwapodmiotu LIKE ? OR nip LIKE ? OR email LIKE ?";
        }
        
        $count_stmt = $conn->prepare($count_sql);
        $likeQuery = "%" . $searchQuery . "%";
        if ($kategoria > 0 && !empty($searchQuery)) {
            $count_stmt->bind_param("isss", $kategoria, $likeQuery, $likeQuery, $likeQuery);
        } elseif ($kategoria > 0) {
            $count_stmt->bind_param("i", $kategoria);
        } elseif (!empty($searchQuery)) {
            $count_stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
        }
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_rows = $count_result->fetch_assoc()['total'];
        $count_stmt->close();
        $total_pages = ceil($total_rows / $per_page);
        if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
        $offset = ($page - 1) * $per_page;
        
        $sql = "SELECT DISTINCT lp, nip, regon, nazwapodmiotu, nazwisko, imie, telefon, email, adreswww, kodpocztowy, powiat, gmina, miejscowosc, ulica, nrbudynku, nrlokalu FROM firmy";
        if ($kategoria > 0) {
            $sql .= " JOIN firma_kategoria fk ON firmy.lp = fk.firma_lp WHERE fk.kategoria_id = ?";
            if (!empty($searchQuery)) {
                $sql .= " AND (nazwapodmiotu LIKE ? OR nip LIKE ? OR email LIKE ?)";
            }
        } elseif (!empty($searchQuery)) {
            $sql .= " WHERE nazwapodmiotu LIKE ? OR nip LIKE ? OR email LIKE ?";
        }
        $sql .= " LIMIT ? OFFSET ?";
        
        $stmt = $conn->prepare($sql);
        if ($kategoria > 0 && !empty($searchQuery)) {
            $stmt->bind_param("isssii", $kategoria, $likeQuery, $likeQuery, $likeQuery, $per_page, $offset);
        } elseif ($kategoria > 0) {
            $stmt->bind_param("iii", $kategoria, $per_page, $offset);
        } elseif (!empty($searchQuery)) {
            $stmt->bind_param("sssii", $likeQuery, $likeQuery, $likeQuery, $per_page, $offset);
        } else {
            $stmt->bind_param("ii", $per_page, $offset);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<table border='1' class='table'>";
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
            echo "<td><input type='button' value='Edytuj' class='edit' onclick=\"window.location.href='edit_company.php?lp=" . urlencode($row['lp']) . "'\"></td>";
            echo "<td><input type='button' value='Usuń' class='delete' onclick=\"window.location.href='delete_company.php?lp=" . urlencode($row['lp']) . "'\"></td>";
            echo "</tr>";
        }
        echo "</table>";

        if ($total_pages > 1) {
            echo "<div style='text-align: center; margin-top: 20px;'>";
            echo "Strona $page z $total_pages<br>";
            $search_param = !empty($searchQuery) ? "&search=" . urlencode($searchQuery) : "";
            $kategoria_param = ($kategoria > 0) ? "&kategoria=" . $kategoria : "";
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . $kategoria_param . $search_param . "'>Poprzednia</a> ";
            }
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . $kategoria_param . $search_param . "'>Następna</a>";
            }
            echo "</div>";
        }
        $stmt->close();
        $conn->close();
    ?>
    </div>
    <script>
        document.getElementById('toggleFormBtn').addEventListener('click', function() {
            let formDiv = document.getElementById('form');
            let searchBox = document.getElementById('searchbox');
            let companyList = document.getElementById('companyList');

            if (formDiv.classList.contains('hidden')) {
                formDiv.classList.remove('hidden');
                searchBox.style.width = '75%';
                companyList.style.width = '75%';
            } else {
                formDiv.classList.add('hidden');
                searchBox.style.width = '98%';
                companyList.style.width = '98%';
            }
        });
    </script>
    </body>
</html>