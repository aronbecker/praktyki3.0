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
        <input type="text" id="search" name="search" placeholder="Szukaj...">
        <input type="submit" value="Szukaj" id="searchBtn">
    </form>
    </div>
    <?php
    $adminStmt = $conn->prepare("SELECT admin FROM users WHERE email = ?");
    $adminStmt->bind_param("s", $_SESSION['login']);
    $adminStmt->execute();
    $adminStmt->bind_result($isAdmin);
    $adminStmt->fetch();
    $adminStmt->close();

    if ($isAdmin == 1) {
        echo "<input type='button' value='Panel administracyjny' id='adminBtn' onclick=\"window.location.href='admin.php'\">";
    }
    ?>
    <div class="companyList" id="companyList">
    <?php
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $sql = "SELECT lp, nazwapodmiotu, nazwisko, imie, telefon, email FROM firmy";
        
        if (!empty($searchQuery)) {
            $sql .= " WHERE nazwapodmiotu LIKE ? OR email LIKE ?";
            $stmt = $conn->prepare($sql);
            $likeQuery = "%" . $searchQuery . "%";
            $stmt->bind_param("ss", $likeQuery, $likeQuery);
        } else {
            $stmt = $conn->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<table border='1'>";
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
        $stmt->close();
        $conn->close();
    ?>
    </div>
    </div>
</body>
</html>