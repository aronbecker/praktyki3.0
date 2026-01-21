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
    <link rel="stylesheet" href="style/styl.css">
</head>
<body>
    <input type="button" value="Wyloguj się" onclick="window.location.href='login.php'" id="logoutBtn">
    <div class="Dheader">
    <h1 style="text-align: center;">Skoki</h1>
    </div>
    <div id="divination">
    <form method="GET" action="">
        <input type="text" id="search" name="search" placeholder="Szukaj...">
        <input type="submit" value="Szukaj" id="searchBtn">
    </form>
    </div>
    <?php
    if ($_SESSION['admin'] == 1) {
        echo "<input type='button' value='Panel administracyjny' id='adminBtn' onclick=\"window.location.href='admin.php'\">";
    }
    ?>
    <div class="companyList" id="companyList">
    <?php
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $per_page = 25;
        $offset = ($page - 1) * $per_page;
        
        $count_sql = "SELECT COUNT(*) as total FROM firmy";
        if (!empty($searchQuery)) {
            $count_sql .= " WHERE nazwapodmiotu LIKE ? OR email LIKE ?";
            $count_stmt = $conn->prepare($count_sql);
            $likeQuery = "%" . $searchQuery . "%";
            $count_stmt->bind_param("ss", $likeQuery, $likeQuery);
        } else {
            $count_stmt = $conn->prepare($count_sql);
        }
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_rows = $count_result->fetch_assoc()['total'];
        $count_stmt->close();
        $total_pages = ceil($total_rows / $per_page);
        if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
        $offset = ($page - 1) * $per_page;
        
        $sql = "SELECT lp, nazwapodmiotu, nazwisko, imie, telefon, email FROM firmy";
        
        if (!empty($searchQuery)) {
            $sql .= " WHERE nazwapodmiotu LIKE ? OR email LIKE ?";
            $sql .= " LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $likeQuery = "%" . $searchQuery . "%";
            $stmt->bind_param("ssii", $likeQuery, $likeQuery, $per_page, $offset);
        } else {
            $sql .= " LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $per_page, $offset);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        <div class="table-wrapper">
        <?php
        echo "<table border='1'  class='data-table'>";
        echo "<tr><th>LP</th><th>Nazwa</th><th>Nazwisko</th><th>Imię</th><th>Telefon</th><th>Email</th><th>Szczegóły</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['lp']) . "</td>";
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
        <?php

        if ($total_pages > 1) {
            echo "<div style='text-align: center; margin-top: 20px;'>";
            echo "Strona $page z $total_pages<br>";
            $search_param = !empty($searchQuery) ? "&search=" . urlencode($searchQuery) : "";
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . $search_param . "'>Poprzednia</a> ";
            }
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . $search_param . "'>Następna</a>";
            }
            echo "</div>";
        }
        $stmt->close();
        $conn->close();
    ?>
    </div>
    </div>
</body>
</html>