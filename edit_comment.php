<?php
include_once 'class/DBManager.php';
include_once 'class/komentarze.php';

if(isset($_POST['comment_id']) && isset($_POST['lp']) && isset($_POST['comment'])) {
    $comment_id = (int)$_POST['comment_id'];
    $lp = (int)$_POST['lp'];
    $komentarze = new Komentarze();
    
    $data = [
        'tresc' => $_POST['comment'],
        'ocena' => (int)$_POST['ocena']
    ];
    
    if ($komentarze->update($comment_id, $data)) {
        header("Location: company_details.php?lp=" . urlencode($lp));
        exit();
    } else {
        echo "Błąd podczas aktualizacji komentarza.";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>