<?php
include 'dbmanager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lp']) && isset($_POST['kategoria'])) {
    $kategoria = trim($_POST['kategoria']);
    $lp = $_POST['lp'];

    if (empty($kategoria)) {
        echo "Kategoria nie może być pusta.";
        exit();
    }

    // Check if category exists
    $stmt = $conn->prepare("SELECT id FROM kategorie WHERE nazwa = ?");
    $stmt->bind_param("s", $kategoria);
    $stmt->execute();
    $stmt->bind_result($kategoria_id);
    if ($stmt->fetch()) {
        $stmt->close();
        // Insert into junction table
        $stmt = $conn->prepare("INSERT INTO firma_kategoria (firma_lp, kategoria_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $lp, $kategoria_id);
        if ($stmt->execute()) {
            echo "Kategoria została dodana pomyślnie.";
            header("Location: edit_company.php?lp=" . urlencode($lp));
            exit();;
        } else {
            echo "Błąd podczas dodawania kategorii: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Nie znaleziono takiej kategorii.";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}

$conn->close();
?>