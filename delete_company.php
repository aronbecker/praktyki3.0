<?php
include 'dbmanager.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lp'])) {
    $lp = $_GET['lp'];
    $stmt = $conn->prepare("DELETE FROM company WHERE lp = ?");
    $stmt->bind_param("i", $lp);
    if ($stmt->execute()) {
        echo "Firma została usunięta pomyślnie.";
    } else {
        echo "Błąd: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
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