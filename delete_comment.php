<?php
include_once 'class/DBManager.php';
include_once 'class/komentarze.php';

if(isset($_POST['comment_id'])) {
    $comment_id = (int)$_POST['comment_id'];
    $lp = (int)$_POST['lp'];
    $komentarze = new Komentarze();
    
    if ($komentarze->delete($comment_id)) {
        header("Location: company_details.php?lp=" . urlencode($lp));
        exit();
    } else {
        echo "Błąd podczas usuwania komentarza.";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>