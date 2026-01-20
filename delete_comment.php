<?php
include 'dbmanager.php';
if(isset($_POST['comment_id'])){
    $commnent_id = $_POST['comment_id'];
    $stmt = $conn->prepare("DELETE FROM komentarze WHERE id = ?");
    $stmt->bind_param("i", $commnent_id);
    if ($stmt->execute()) {
        header("Location: company_details.php?lp=" . urlencode($_POST['lp']));
        exit();
    } else {
        echo "Błąd podczas usuwania komentarza.";
}
    $stmt->close();
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}