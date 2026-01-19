<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style2.css">
</head>
<body>
<h2>Więcej danych o firmie</h2>
<input type="button" value="Powrót do strony głównej" onclick="window.location.href='index.php'" style="margin-bottom: 20px;">
<div>
    <?php
include 'dbmanager.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lp'])) {
    $lp = $_GET['lp'];
    $stmt = $conn->prepare("SELECT lp, NIP, REGON, nazwapodmiotu, imie, nazwisko, telefon, email, adreswww, kodpocztowy, powiat, gmina, miejscowosc, ulica, nrbudynku, nrlokalu FROM firmy WHERE lp = ?");
    $stmt->bind_param("i", $lp);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo "<p><strong>LP:</strong> " . htmlspecialchars($row['lp']) . "</p>";
        echo "<p><strong>NIP:</strong> " . htmlspecialchars($row['NIP']) . "</p>";
        echo "<p><strong>REGON:</strong> " . htmlspecialchars($row['REGON']) . "</p>";
        echo "<p><strong>Nazwa:</strong> " . htmlspecialchars($row['nazwapodmiotu']) . "</p>";
        echo "<p><strong>Imię:</strong> " . htmlspecialchars($row['imie']) . "</p>";
        echo "<p><strong>Nazwisko:</strong> " . htmlspecialchars($row['nazwisko']) . "</p>";
        echo "<p><strong>Telefon:</strong> " . htmlspecialchars($row['telefon']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
        echo "<p><strong>Adres WWW:</strong> " . htmlspecialchars($row['adreswww']) . "</p>";
        echo "<p><strong>Kod Pocztowy:</strong> " . htmlspecialchars($row['kodpocztowy']) . "</p>";
        echo "<p><strong>Powiat:</strong> " . htmlspecialchars($row['powiat']) . "</p>";
        echo "<p><strong>Gmina:</strong> " . htmlspecialchars($row['gmina']) . "</p>";
        echo "<p><strong>Miejscowość:</strong> " . htmlspecialchars($row['miejscowosc']) . "</p>";
        echo "<p><strong>Ulica:</strong> " . htmlspecialchars($row['ulica']) . "</p>";
        echo "<p><strong>Numer Budynku:</strong> " . htmlspecialchars($row['nrbudynku']) . "</p>";
        echo "<p><strong>Numer Lokalu:</strong> " . htmlspecialchars($row['nrlokalu']) . "</p>";
    } else {
        echo "Nie znaleziono firmy o podanym LP.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
</div>
</body>
</html>
