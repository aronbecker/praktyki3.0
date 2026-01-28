<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style2.css">
</head>
<body>
    <div style="width:30%">
    <h2>Zarządzaj użytkownikami</h2>
    </div>
    <input type="button" value="Powrót do panelu administratora" onclick="window.location.href='admin.php'" style="margin-bottom: 20px; float: left;">
    <input type="button" value="Dodaj nowego użytkownika" onclick="window.location.href='register.php'" style="margin-bottom: 20px; float: right;">
    <br><br><br>
    <div style="width: 50%; margin-bottom: 20px;">
        <form method="GET" action="">
        <input type="text" id="search" name="search" placeholder="Szukaj..." style="width: 70%; margin-bottom: 10px;">
        <input type="submit" value="Szukaj" id="searchBtn" style="float: right;">
    </form>
</div>
<div class='companyList'>
<?php
include_once 'class/DBManager.php';
include_once 'class/users.php';

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$usersObj = new Users();
$usersList = $usersObj->getAll();

if (!empty($searchQuery)) {
    $usersList = array_filter($usersList, function($user) use ($searchQuery) {
        return stripos($user['login'], $searchQuery) !== false || 
               stripos($user['email'], $searchQuery) !== false;
    });
}

if (count($usersList) > 0) {
    echo "<table border='1' class='table'>
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Edytuj</th>
                <th>Usuń</th>
            </tr>";
    foreach ($usersList as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['login']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . ($row['admin'] ? 'Tak' : 'Nie') . "</td>";
        echo "<td><input type='button' value='Edytuj' class='edit' onclick=\"window.location.href='edit_user.php?id=" . urlencode($row['id']) . "'\"></td>";
        echo "<td><input type='button' value='Usuń' class='delete' onclick=\"window.location.href='delete_user.php?id=" . urlencode($row['id']) . "'\"></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "Brak użytkowników do wyświetlenia.";
}
?>
</div>
</body>
</html>