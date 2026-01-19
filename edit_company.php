<?php
include 'dbmanager.php';
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lp'])) {
    $lp = $_GET['lp'];
    $stmt = $conn->prepare("SELECT nip, regon, nazwapodmiotu, nazwisko, imie, telefon, email, adreswww, kodpocztowy, powiat, gmina, miejscowosc, ulica, nrbudynku, nrlokalu FROM firmy WHERE lp = ?");
    $stmt->bind_param("i", $lp);
    $stmt->execute();
    $stmt->bind_result($nip, $regon, $nazwapodmiotu, $nazwisko, $imie, $telefon, $email, $adreswww, $kodpocztowy, $powiat, $gmina, $miejscowosc, $ulica, $nrbudynku, $nrlokalu);
    if ($stmt->fetch()) {
        // Dane firmy zostały pobrane pomyślnie
    } else {
        echo "Nie znaleziono firmy o podanym identyfikatorze.";
        exit();;
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lp = $_POST['lp'];
    $nip = $_POST['NIP'];
    $regon = $_POST['REGON'];
    $nazwapodmiotu = $_POST['nazwa'];
    $nazwisko = $_POST['nazwisko'];
    $imie = $_POST['imie'];
    $telefon = $_POST['nr_telefonu'];
    $email = $_POST['email'];
    $adreswww = $_POST['adreswww'];
    $kodpocztowy = $_POST['kodpocztowy'];
    $powiat = $_POST['powiat'];
    $gmina = $_POST['gmina'];
    $miejscowosc = $_POST['miejscowosc'];
    $ulica = $_POST['ulica'];
    $nrbudynku = $_POST['nrbudynku'];
    $nrlokalu = $_POST['nrlokalu'];
    $stmt = $conn->prepare("UPDATE firmy SET nip = ?, regon = ?, nazwapodmiotu = ?, nazwisko = ?, imie = ?, telefon = ?, email = ?, adreswww = ?, kodpocztowy = ?, powiat = ?, gmina = ?, miejscowosc = ?, ulica = ?, nrbudynku = ?, nrlokalu = ? WHERE lp = ?");
    $stmt->bind_param("sssssssssssssssi", $nip, $regon, $nazwapodmiotu, $nazwisko, $imie, $telefon, $email, $adreswww, $kodpocztowy, $powiat, $gmina, $miejscowosc, $ulica, $nrbudynku, $nrlokalu, $lp);
    if ($stmt->execute()) {
        echo "Dane firmy zostały zaktualizowane pomyślnie.";
        header("Location: admin.php");
        exit();
    } else {
        echo "Błąd: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Nieprawidłowe żądanie.";
    exit();;
}
        ?>
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edytuj Firmę</title>
            <link rel="stylesheet" href="style/style2.css">
            <style>
                label{
                    display: block;
                    text-align: center;
                    margin-top: 10px;
                }
                input{
                    margin-left:auto;
                    margin-right:auto;
                    display: block;
                }
            </style>
        </head>
        <body>
            
            <h1>Edytuj Firmę</h1><div style="width: 30%; margin: auto;">
            <form method="POST" action="edit_firmy.php">
                <input type="hidden" name="lp" value="<?php echo htmlspecialchars($lp); ?>">
                <label for="NIP">NIP:</label><br>
                <input type="text" id="NIP" name="NIP" value="<?php echo htmlspecialchars($nip); ?>"><br>
                <label for="REGON">REGON:</label><br>
                <input type="text" id="REGON" name="REGON" value="<?php echo htmlspecialchars($regon); ?>"><br>
                <label for="nazwa">Nazwa:</label><br>
                <input type="text" id="nazwapodmiotu" name="nazwapodmiotu" value="<?php echo htmlspecialchars($nazwapodmiotu); ?>" required><br>
                <label for="imie">Imię:</label>
                <input type="text" id="imie" name="imie" value="<?php echo htmlspecialchars($imie); ?>"><br>
                <label for="nazwisko">Nazwisko:</label>
                <input type="text" id="nazwisko" name="nazwisko" value="<?php echo htmlspecialchars($nazwisko); ?>"><br>
                <label for="nr_telefonu">Numer Telefonu:</label><br>
                <input type="text" id="nr_telefonu" name="nr_telefonu" value="<?php echo htmlspecialchars($telefon); ?>"><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
                <label for="adreswww">Adres WWW:</label><br>
                <input type="text" id="adreswww" name="adreswww" value="<?php echo htmlspecialchars($adreswww); ?>"><br>
                <label for="kodpocztowy">Kod Pocztowy:</label><br>
                <input type="text" id="kodpocztowy" name="kodpocztowy" value="<?php echo htmlspecialchars($kodpocztowy); ?>"><br>
                <label for="powiat">Powiat:</label><br>
                <input type="text" id="powiat" name="powiat" value="<?php echo htmlspecialchars($powiat); ?>"><br>
                <label for="gmina">Gmina:</label><br>
                <input type="text" id="gmina" name="gmina" value="<?php echo htmlspecialchars($gmina); ?>"><br>
                <label for="miejscowosc">Miejscowość:</label><br>
                <input type="text" id="miejscowosc" name="miejscowosc" value="<?php echo htmlspecialchars($miejscowosc); ?>"><br>
                <label for="ulica">Ulica:</label><br>
                <input type="text" id="ulica" name= "ulica"value="<?php echo htmlspecialchars($ulica); ?>"><br>
                <label for="nrbudynku">Numer Budynku:</label><br>
                <input type="text" id="nrbudynku" name="nrbudynku" value="<?php echo htmlspecialchars($nrbudynku); ?>"><br>
                <label for="nrlokalu">Numer Lokalu:</label><br>
                <input type="text" id="nrlokalu" name="nrlokalu" value="<?php echo htmlspecialchars($nrlokalu); ?>"><br><br>
                <input type="submit" value="Zatwierdź">
            </form>
            </div>
            <input type="button" value="Powrót do panelu administracyjnego" onclick="window.location.href='admin.php'">
        </body>
        </html>