<?php
include 'dbmanager.php';
if(isset($_POST['comment_id']) && isset($_POST['lp']) && isset($_POST['comment'])) {
    $comment_id = $_POST['comment_id'];
    $lp = $_POST['lp'];
    $new_comment = $_POST['comment'];

    $stmt = $conn->prepare("UPDATE komentarze SET tresc = ?, data_mod = NOW() WHERE id = ?");
    $stmt->bind_param("si", $new_comment, $comment_id);
    if ($stmt->execute()) {
        header("Location: company_details.php?lp=" . urlencode($lp));
        exit();
    } else {
        echo "Błąd podczas aktualizacji komentarza.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>