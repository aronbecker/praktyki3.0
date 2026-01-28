<?php
include_once 'class/DBManager.php';
include_once 'class/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $users = new Users();
    
    if ($users->delete($id)) {
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Błąd podczas usuwania użytkownika.";
        echo "<br><input type='button' value='Powrót' onclick='window.history.back()'>";
    }
} else {
    echo "Nieprawidłowe żądanie.";
    echo "<br><input type='button' value='Powrót' onclick='window.history.back()'>";
}
?>