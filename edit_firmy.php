<?php
include 'dbmanager.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lp'])) {
    $lp = $_POST['lp'];
    $NIP = $_POST['NIP'];
    $REGON = $_POST['REGON'];
    $nazwapodmiotu = $_POST['nazwapodmiotu'];
    $nazwisko = $_POST['nazwisko'];
    $imie = $_POST['imie'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];
    $adreswww = $_POST['adreswww'];
    $kodpocztowy = $_POST['kodpocztowy'];
    $powiat = $_POST['powiat'];
    $gmina = $_POST['gmina'];
    $miejscowosc = $_POST['miejscowosc'];
    $ulica = $_POST['ulica'];
    $nrbudynku = $_POST['nrbudynku'];
    $nrlokalu = $_POST['nrlokalu'];

    $stmt = $conn->prepare("UPDATE firmy SET NIP=?, REGON=?, nazwapodmiotu=?, nazwisko=?, imie=?, telefon=?, email=?, adreswww=?, kodpocztowy=?, powiat=?, gmina=?, miejscowosc=?, ulica=?, nrbudynku=?, nrlokalu=? WHERE lp=?");
    $stmt->bind_param("sssssssssssssssi", $NIP, $REGON, $nazwapodmiotu, $nazwisko, $imie, $telefon, $email, $adreswww, $kodpocztowy, $powiat, $gmina, $miejscowosc, $ulica, $nrbudynku, $nrlokalu, $lp);

    if ($stmt->execute()) {
        header("Location: admin.php");
    } else {
        echo "Błąd podczas aktualizacji danych firmy. <br> <input type='button' value='Powrót' onclick='window.history.back()'>";
    }

    $stmt->close();
} else {
    echo "Nieprawidłowe żądanie. <br> <input type='button' value='Powrót' onclick='window.history.back()'>";
}