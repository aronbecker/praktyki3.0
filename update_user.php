<?php
include_once 'class/DBManager.php';
include_once 'class/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $users = new Users();

    $users->update($id, [
        'login' => $_POST['login'],
        'pass'  => $_POST['pass'],
        'email' => $_POST['email'],
    ]);
    header("Location: manage_users.php");
    exit();
} else {
    echo "Nieprawidłowe żądanie. <br> <input type='button' value='Powrót' onclick='window.history.back()'>";
}
?>