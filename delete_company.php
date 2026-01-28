<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lp'])) {
    $lp = (int)$_GET['lp'];
    $firmy = new Firmy();
    
    if ($firmy->delete($lp)) {
        echo "Firma została usunięta pomyślnie.";
        header("Location: admin.php");
        exit();
    } else {
        echo "Błąd podczas usuwania firmy.";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuwanie Firmy</title>
</head>
<body>
    <input type="button" value="Powrót do panelu administracyjnego" onclick="window.location.href='admin.php'">
</body>
</html>