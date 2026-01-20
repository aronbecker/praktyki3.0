<?php
session_start();
?>
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
<div style="margin-bottom: 30px;">
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
<div id="commentSection">
    <h3>Komentarze</h3>
    <form action="add_comment.php" method="POST">
        <input type="hidden" name="lp" value="<?php echo isset($_GET['lp']) ? htmlspecialchars($_GET['lp']) : ''; ?>">
        <textarea name="comment" rows="4" cols="100" placeholder="Dodaj komentarz..."></textarea><br>
        <input type="submit" value="Dodaj Komentarz">
    </form>
    <?php
    include 'dbmanager.php';
    if (isset($_GET['lp'])) {
        $lp = $_GET['lp'];

        $stmt = $conn->prepare("SELECT id, tresc, autor, data_utworzenia, data_mod, user_id FROM komentarze WHERE firma_lp = ? ORDER BY data_utworzenia DESC");
        $stmt->bind_param("i", $lp);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p><em>Autor: " . htmlspecialchars($row['autor']) . "&nbsp;&nbsp;&nbsp;&nbsp;Data utworzenia: " . htmlspecialchars($row['data_utworzenia']) . "</em></p>";
                if (!is_null($row['data_mod'])) {
                    echo "<p><em>Data modyfikacji: " . htmlspecialchars($row['data_mod']) . "</em></p>";
                }
                echo "<p>" . htmlspecialchars($row['tresc']) . "</p>";
                if($row['user_id'] == $_SESSION['user_id']) {
                    echo "<form action='edit_comment.php' method='POST'>";
                    echo "<input type='hidden' name='comment_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<input type='hidden' name='lp' value='" . htmlspecialchars($lp) . "'>";
                    echo "<textarea name='comment' rows='4' cols='100'>" . htmlspecialchars($row['tresc']) . "</textarea><br>";
                    echo "<input type='submit' value='Edytuj komentarz'>";
                    echo "</form>";
                    echo "<form action='delete_comment.php' method='POST' onsubmit='return confirm(\"Czy na pewno chcesz usunąć ten komentarz?\");'>";
                    echo "<input type='hidden' name='comment_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<input type='hidden' name='lp' value='" . htmlspecialchars($lp) . "'>";
                    echo "<input type='submit' value='Usuń komentarz'>";
                    echo "</form>";
                }
                
                
                echo "</div><hr>";
            }
        } else {
            echo "<p>Brak komentarzy dla tej firmy.</p>";
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "Nieprawidłowe żądanie.";
    }
    ?>
</body>
</html>
