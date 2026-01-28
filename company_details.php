<?php
session_start();
include_once 'class/DBManager.php';
include_once 'class/firmy.php';
include_once 'class/komentarze.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style2.css">
    <style>
        p>span{
            cursor: pointer;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div>
<h2>Więcej danych o firmie</h2>
</div>
<input type="button" value="Powrót do strony głównej" onclick="window.location.href='index.php'" style="margin-bottom: 20px;">
<div style="margin-bottom: 30px;">
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lp'])) {
    $lp = (int)$_GET['lp'];
    $firmy = new Firmy();
    $firma = $firmy->getById($lp);
    
    if ($firma) {
        echo "<p><strong>LP:</strong> " . htmlspecialchars($firma['Lp']) . "</p>";
        echo "<p><strong>NIP:</strong> " . htmlspecialchars($firma['Nip']) . "</p>";
        echo "<p><strong>REGON:</strong> " . htmlspecialchars($firma['Regon']) . "</p>";
        echo "<p><strong>Nazwa:</strong> " . htmlspecialchars($firma['NazwaPodmiotu']) . "</p>";
        echo "<p><strong>Imię:</strong> " . htmlspecialchars($firma['Imie']) . "</p>";
        echo "<p><strong>Nazwisko:</strong> " . htmlspecialchars($firma['Nazwisko']) . "</p>";
        echo "<p><strong>Telefon:</strong> " . htmlspecialchars($firma['Telefon']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($firma['Email']) . "</p>";
        echo "<p><strong>Adres WWW:</strong> " . htmlspecialchars($firma['AdresWWW']) . "</p>";
        echo "<p><strong>Kod Pocztowy:</strong> " . htmlspecialchars($firma['KodPocztowy']) . "</p>";
        echo "<p><strong>Powiat:</strong> " . htmlspecialchars($firma['Powiat']) . "</p>";
        echo "<p><strong>Gmina:</strong> " . htmlspecialchars($firma['Gmina']) . "</p>";
        echo "<p><strong>Miejscowość:</strong> " . htmlspecialchars($firma['Miejscowosc']) . "</p>";
        echo "<p><strong>Ulica:</strong> " . htmlspecialchars($firma['Ulica']) . "</p>";
        echo "<p><strong>Numer Budynku:</strong> " . htmlspecialchars($firma['NrBudynku']) . "</p>";
        echo "<p><strong>Numer Lokalu:</strong> " . htmlspecialchars($firma['NrLokalu']) . "</p>";
    } else {
        echo "Nie znaleziono firmy o podanym LP.";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
</div>
<div id="commentSection">
    <h3>Komentarze</h3>
    <form action="add_comment.php" method="POST">
        <input type="hidden" name="lp" value="<?php echo isset($_GET['lp']) ? htmlspecialchars($_GET['lp']) : ''; ?>">
        <input type="hidden" id="ocena" name="ocena" value="1">
        <p>Ocena: <span id="stars1">★</span><span id="stars2">☆</span><span id="stars3">☆</span><span id="stars4">☆</span><span id="stars5">☆</span></p>
        <textarea name="comment" rows="4" cols="100" placeholder="Dodaj komentarz..."></textarea><br>
        <input type="submit" value="Dodaj Komentarz" style="display: inline;">
    </form>
    <?php
    if (isset($_GET['lp'])) {
        $lp = (int)$_GET['lp'];
        $komentarze = new Komentarze();
        $komentarzeLista = $komentarze->getByFirma($lp);
        
        if (count($komentarzeLista) > 0) {
            foreach ($komentarzeLista as $row) {
                echo "<div class='comment'>";
                echo "<p><em>Autor: " . htmlspecialchars($row['autor']) . "&nbsp;&nbsp;&nbsp;&nbsp;Data utworzenia: " . htmlspecialchars($row['data_utworzenia']) . "</em></p>";
                if (!is_null($row['data_mod'])) {
                    echo "<p><em>Data modyfikacji: " . htmlspecialchars($row['data_mod']) . "</em></p>";
                }
                echo "<p>Ocena: " . str_repeat('★', $row['ocena']) . str_repeat('☆', 5 - $row['ocena']) . "</p>";
                echo "<p>" . htmlspecialchars($row['tresc']) . "</p>";
                if($row['user_id'] == $_SESSION['user_id']) {
                    echo "<hr>";
                    echo "<form action='edit_comment.php' method='POST'>";
                    echo "<input type='hidden' name='comment_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<input type='hidden' name='lp' value='" . htmlspecialchars($lp) . "'>";
                    echo "<input type='hidden' id='ocena_edit_" . $row['id'] . "' name='ocena' value='" . htmlspecialchars($row['ocena']) . "'>";
                    $ocena = $row['ocena'];
                    echo "<p>Ocena: ";
                    for($i=1; $i<=5; $i++){
                        $star = $i <= $ocena ? '★' : '☆';
                        echo "<span id='edit_stars_" . $row['id'] . "_" . $i . "' style='cursor: pointer; font-size: 24px;'>$star</span>";
                    }
                    echo "</p>";
                    echo "<textarea name='comment' rows='4' cols='100'>" . htmlspecialchars($row['tresc']) . "</textarea><br>";
                    echo "<input type='submit' value='Edytuj komentarz' style='display:inline;'>";
                    echo "</form>";
                    echo "<script>
                    const stars_" . $row['id'] . " = document.querySelectorAll('span[id^=\"edit_stars_" . $row['id'] . "_\"]');
                    stars_" . $row['id'] . ".forEach((star, index) => {
                        star.addEventListener('click', () => {
                            stars_" . $row['id'] . ".forEach((s, i) => {
                                s.textContent = i <= index ? '★' : '☆';
                            });
                            document.getElementById('ocena_edit_" . $row['id'] . "').value = index + 1;
                        });
                    });
                    </script>";
                    echo "<form action='delete_comment.php' method='POST' onsubmit='return confirm(\"Czy na pewno chcesz usunąć ten komentarz?\");'>";
                    echo "<input type='hidden' name='comment_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<input type='hidden' name='lp' value='" . htmlspecialchars($lp) . "'>";
                    echo "<input type='submit' value='Usuń komentarz' style='display:inline;'>";
                    echo "</form>";
                }
                
                
                echo "</div><hr>";
            }
        } else {
            echo "<p>Brak komentarzy dla tej firmy.</p>";
        }
    } else {
        echo "Nieprawidłowe żądanie.";
    }
    ?>
    <script>
    const stars = document.querySelectorAll('#commentSection span[id^="stars"]');
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            stars.forEach((s, i) => {
                s.textContent = i <= index ? '★' : '☆';
                document.getElementById('ocena').value = index + 1;
            });
        });
    });
    </script>
</body>
</html>
