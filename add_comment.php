<?php
include 'dbmanager.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['lp'])) {
    $comment = trim($_POST['comment']);
    $lp = $_POST['lp'];

    if (empty($comment)) {
        echo "Komentarz nie może być pusty.";
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO komentarze (firma_lp, tresc, autor, user_id, ocena) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $lp, $comment, $_SESSION['login'], $_SESSION['user_id'], $_POST['ocena']);

    if ($stmt->execute()) {
        echo "Komentarz został dodany pomyślnie.";
        header("Location: company_details.php?lp=" . urlencode($lp));
        exit();;
    } else {
        echo "Błąd podczas dodawania komentarza: " . $stmt->error;

    }
} else {
    echo "Nieprawidłowe żądanie.";
}
$stmt->close(); 
$conn->close();
?>