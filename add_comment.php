<?php
// include 'dbmanager.php';
// session_start();
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['lp'])) {
//     $comment = trim($_POST['comment']);
//     $lp = $_POST['lp'];

//     if (empty($comment)) {
//         echo "Komentarz nie może być pusty.";
//         exit();
//     }
//     $stmt = $conn->prepare("INSERT INTO komentarze (firma_lp, tresc, autor, user_id, ocena) VALUES (?, ?, ?, ?, ?)");
//     $stmt->bind_param("issii", $lp, $comment, $_SESSION['login'], $_SESSION['user_id'], $_POST['ocena']);

//     if ($stmt->execute()) {
//         echo "Komentarz został dodany pomyślnie.";
//         header("Location: company_details.php?lp=" . urlencode($lp));
//         exit();;
//     } else {
//         echo "Błąd podczas dodawania komentarza: " . $stmt->error;

//     }
// } else {
//     echo "Nieprawidłowe żądanie.";
// }
// $stmt->close(); 
// $conn->close();

require_once 'class/DBManager.php';
require_once 'class/komentarze.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Nieprawidłowe żądanie.";
    exit;
}

if (
    !isset($_POST['comment'], $_POST['lp'], $_POST['ocena'], $_SESSION['user_id'])
) {
    echo "Brak wymaganych danych.";
    exit;
}

$comment = trim($_POST['comment']);
$firmaLp = (int)$_POST['lp'];
$ocena   = (int)$_POST['ocena'];

if ($comment === '') {
    echo "Komentarz nie może być pusty.";
    exit;
}

if ($ocena < 1 || $ocena > 5) {
    echo "Ocena musi być w zakresie 1–5.";
    exit;
}

try {
    $komentarze = new Komentarze();

    // (opcjonalnie) blokada wielu komentarzy jednego usera
    if ($komentarze->userHasCommented($firmaLp, $_SESSION['user_id'])) {
        echo "Już dodałeś komentarz do tej firmy.";
        exit;
    }

    $komentarze->create([
        'tresc'    => $comment,
        'autor'    => $_SESSION['login'] ?? null,
        'user_id'  => $_SESSION['user_id'],
        'firma_lp' => $firmaLp,
        'ocena'    => $ocena
    ]);

    header("Location: company_details.php?lp=" . urlencode($firmaLp));
    exit;

} catch (Throwable $e) {
    // loguj do pliku, nie wyświetlaj userowi
    error_log($e->getMessage());
    echo "Wystąpił błąd podczas dodawania komentarza.";
}

?>