<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['NIP'] ?? null;
    $regon = $_POST['REGON'] ?? null;
    $nazwapodmiotu = $_POST['nazwa'] ?? null;
    $nazwisko = $_POST['nazwisko'] ?? null;
    $imie = $_POST['imie'] ?? null;
    $telefon = $_POST['nr_telefonu'] ?? null;
    $email = $_POST['email'] ?? null;
    $adresWWW = $_POST['adreswww'] ?? null;
    $kodpocztowy = $_POST['kodpocztowy'] ?? null;
    $powiat = $_POST['powiat'] ?? null;
    $gmina = $_POST['gmina'] ?? null;
    $miejscowosc = $_POST['miejscowosc'] ?? null;
    $ulica = $_POST['ulica'] ?? null;
    $nrbudynku = $_POST['nrbudynku'] ?? null;
    $nrlokalu = $_POST['nrlokalu'] ?? null;

    // $stmt = $conn->prepare("INSERT INTO firmy (nip, regon, nazwapodmiotu, nazwisko, imie, telefon, email, adreswww, kodpocztowy, powiat, gmina, miejscowosc, ulica, nrbudynku, nrlokalu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // $stmt->bind_param("sssssssssssssss", $nip, $regon, $nazwapodmiotu, $nazwisko, $imie, $telefon, $email, $adresWWW, $kodpocztowy, $powiat, $gmina, $miejscowosc, $ulica, $nrbudynku, $nrlokalu);
    // if ($stmt->execute()) {
    //     echo "Nowa firma została dodana pomyślnie.";
    // } else {
    //     echo "Błąd: " . $stmt->error;
    // }
    // $stmt->close();
    // $conn->close();

    $firmy = new Firmy();
    $id = $firmy->create([
        'Nip' => $nip,
        'Regon' => $regon,
        'NazwaPodmiotu' => $nazwapodmiotu,
        'Nazwisko' => $nazwisko,
        'Imie' => $imie,
        'Telefon' => $telefon,
        'Email' => $email,
        'AdresWWW' => $adresWWW,
        'KodPocztowy' => $kodpocztowy,
        'Powiat' => $powiat,
        'Gmina' => $gmina,
        'Miejscowosc' => $miejscowosc,
        'Ulica' => $ulica,
        'NrBudynku' => $nrbudynku,
        'NrLokalu' => $nrlokalu
    ]);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <input type="button" value="Powrót do panelu administracyjnego" onclick="window.location.href='admin.php'">
</body>
</html>