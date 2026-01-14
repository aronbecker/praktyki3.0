<?php
    include 'dbmanager.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $nazwa = $_POST['nazwa'];
        $ulica = $_POST['ulica'];
        $kod_pocztowy = $_POST['kod_pocztowy'];
        $NIP = $_POST['NIP'];
        $REGON = $_POST['REGON'];
        $nr_telefonu = $_POST['nr_telefonu'];
        $email = $_POST['email'];

        if(!empty($id) && empty($nazwa) && empty($ulica) && empty($kod_pocztowy) && empty($NIP) && empty($REGON) && empty($nr_telefonu) && empty($email)) {
                $stmt = $conn->prepare("DELETE FROM firmy WHERE id = ?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    echo "Firma została usunięta pomyślnie.";
                } else {
                    echo "Błąd podczas usuwania firmy: " . $stmt->error;
                }
            } else if (!empty($id)) {
                $stmt = $conn->prepare("UPDATE firmy SET nazwa = ?, ulica = ?, kod_pocztowy = ?, NIP = ?, REGON = ?, nr_telefonu = ?, email = ? WHERE id = ?");
                $stmt->bind_param("sssiiisi", $nazwa, $ulica, $kod_pocztowy, $NIP, $REGON, $nr_telefonu, $email, $id);
                if ($stmt->execute()) {
                    echo "Firma została zaktualizowana pomyślnie.";
                } else {
                    echo "Błąd podczas aktualizacji firmy: " . $stmt->error;
                }
            } else {
            $stmt = $conn->prepare("INSERT INTO firmy (nazwa, ulica, kod_pocztowy, NIP, REGON, nr_telefonu, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiis", $nazwa, $ulica, $kod_pocztowy, $NIP, $REGON, $nr_telefonu, $email);

            if ($stmt->execute()) {
                echo "Firma została dodana pomyślnie.";
            } else {
                echo "Błąd podczas dodawania firmy: " . $stmt->error;
            } 
        }
        $stmt->close();
        $conn->close();
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