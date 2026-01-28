<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';
include_once 'class/kategorie.php';

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lp'])) {
    $lp = (int)$_GET['lp'];
    $firmy = new Firmy();
    $firma = $firmy->getById($lp);
    
    if ($firma) {
        $nip = $firma['Nip'];
        $regon = $firma['Regon'];
        $nazwapodmiotu = $firma['NazwaPodmiotu'];
        $nazwisko = $firma['Nazwisko'];
        $imie = $firma['Imie'];
        $telefon = $firma['Telefon'];
        $email = $firma['Email'];
        $adreswww = $firma['AdresWWW'];
        $kodpocztowy = $firma['KodPocztowy'];
        $powiat = $firma['Powiat'];
        $gmina = $firma['Gmina'];
        $miejscowosc = $firma['Miejscowosc'];
        $ulica = $firma['Ulica'];
        $nrbudynku = $firma['NrBudynku'];
        $nrlokalu = $firma['NrLokalu'];
    } else {
        echo "Nie znaleziono firmy o podanym identyfikatorze.";
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lp = (int)$_POST['lp'];
    $firmy = new Firmy();
    
    $data = [
        'Nip' => $_POST['NIP'],
        'Regon' => $_POST['REGON'],
        'NazwaPodmiotu' => $_POST['nazwapodmiotu'],
        'Nazwisko' => $_POST['nazwisko'],
        'Imie' => $_POST['imie'],
        'Telefon' => $_POST['nr_telefonu'],
        'Email' => $_POST['email'],
        'AdresWWW' => $_POST['adreswww'],
        'KodPocztowy' => $_POST['kodpocztowy'],
        'Powiat' => $_POST['powiat'],
        'Gmina' => $_POST['gmina'],
        'Miejscowosc' => $_POST['miejscowosc'],
        'Ulica' => $_POST['ulica'],
        'NrBudynku' => $_POST['nrbudynku'],
        'NrLokalu' => $_POST['nrlokalu']
    ];
    
    $firmy->update($lp, $data);
    echo "Dane firmy zostały zaktualizowane pomyślnie.";
    header("Location: admin.php");
    exit();
} else {
    echo "Nieprawidłowe żądanie.";
    exit();
}
?>
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edytuj Firmę</title>
            <link rel="stylesheet"href="style/style2.css">
            <style>
                .dimmer {
                    margin-top:auto;
                        background: #000;
                        opacity: 0.5;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        z-index: 0;
                    }
                .hidden{
                    display: none;
                }
            </style>
        </head>
        <body>
            
            <h1>Edytuj Firmę</h1>
            <div style="
            width: 60%;
            padding: 10px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            height: auto;
            border: 2px solid black;>
            <form method="POST" action="edit_firmy.php">
                <div style="float: left; margin-right: 20px;">
                <input type="hidden" name="lp" value="<?php echo htmlspecialchars($lp); ?>">
                <label for="NIP">NIP:</label><br>
                <input type="text" id="NIP" name="NIP" value="<?php echo htmlspecialchars($nip); ?>"><br>
                <label for="REGON">REGON:</label><br>
                <input type="text" id="REGON" name="REGON" value="<?php echo htmlspecialchars($regon); ?>"><br>
                <label for="nazwa">Nazwa:</label><br>
                <input type="text" id="nazwapodmiotu" name="nazwapodmiotu" value="<?php echo htmlspecialchars($nazwapodmiotu); ?>" required><br>
                <label for="imie">Imię:</label><br>
                <input type="text" id="imie" name="imie" value="<?php echo htmlspecialchars($imie); ?>"><br>
                <label for="nazwisko">Nazwisko:</label><br>
                <input type="text" id="nazwisko" name="nazwisko" value="<?php echo htmlspecialchars($nazwisko); ?>"><br>
</div><div style="float: left; margin-right: 20px;">
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
</div><div style="float: left;">
                <label for="gmina">Gmina:</label><br>
                <input type="text" id="gmina" name="gmina" value="<?php echo htmlspecialchars($gmina); ?>"><br>
                <label for="miejscowosc">Miejscowość:</label><br>
                <input type="text" id="miejscowosc" name="miejscowosc" value="<?php echo htmlspecialchars($miejscowosc); ?>"><br>
                <label for="ulica">Ulica:</label><br>
                <input type="text" id="ulica" name= "ulica"value="<?php echo htmlspecialchars($ulica); ?>"><br>
                <label for="nrbudynku">Numer Budynku:</label><br>
                <input type="text" id="nrbudynku" name="nrbudynku" value="<?php echo htmlspecialchars($nrbudynku); ?>"><br>
                <label for="nrlokalu">Numer Lokalu:</label><br>
                <input type="text" id="nrlokalu" name="nrlokalu" value="<?php echo htmlspecialchars($nrlokalu); ?>"><br>
</div><div style="display: block; clear: both; margin-top: 20px;">
                <input type="button" value="Dodaj kategorie" onclick="kategorieOkienko()"><br>
                <input type="submit" value="Zatwierdź">
</div>
            </form>
            </div>
            <div class="dimmer hidden"></div>
            <div class="modal hidden" style="
                position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border: 2px solid black; width: 300px; text-align: center;">
                    <h3>Dodaj kategorię</h3>
                    <form method="POST" action="add_kategoria.php">
                        <input type="hidden" name="lp" value="<?php echo htmlspecialchars($lp); ?>">
                        <select name="kategoria" id="sel">
                        <?php
                        $kategorieObj = new Kategorie();
                        $kategorieList = $kategorieObj->getAll();
                        foreach ($kategorieList as $kat) {
                            echo "<option value='" . htmlspecialchars($kat['nazwa']) . "'>" . htmlspecialchars($kat['nazwa']) . "</option>";
                        }
                        ?>
                        </select>
                        <input type="submit" value="Dodaj">
                    </form>
                    <input type="button" id="closeModal" value="Zamknij">
                </div>
            <input type="button" value="Powrót do panelu administracyjnego" onclick="window.location.href='admin.php'">
            
            <script>
                
                function kategorieOkienko() {
                    let modal = document.querySelector('.modal');
                    let dimmer = document.querySelector('.dimmer');
                    dimmer.classList.remove('hidden');
                    modal.classList.remove('hidden');
                }
                document.getElementById('closeModal').addEventListener('click', function() {
                    let modal = document.querySelector('.modal');
                    let dimmer = document.querySelector('.dimmer');
                    dimmer.classList.add('hidden');
                    modal.classList.add('hidden');
                });
            </script>
        </body>
        </html>