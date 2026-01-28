<?php
include_once 'class/kategorie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lp']) && isset($_POST['kategoria'])) {
    $kategoria = trim($_POST['kategoria']);
    $lp = (int)$_POST['lp'];

    if (empty($kategoria)) {
        echo "Kategoria nie może być pusta.";
        exit();
    }

    try {
        $kategorieObj = new Kategorie();
        $kategorieObj->assignToCompanyByName($kategoria, $lp);
        
        echo "Kategoria została dodana pomyślnie.";
        header("Location: edit_company.php?lp=" . urlencode($lp));
        exit();
    } catch (Throwable $e) {
        error_log($e->getMessage());
        echo "Błąd podczas dodawania kategorii: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>