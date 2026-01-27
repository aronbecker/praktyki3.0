<?php
include 'dbmanager.php';
include_once 'class/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $email = $_POST['email'];
    // $adminSts = $_POST['admin'];

    $users = new Users();

    $users->update((int)$id, [
        'login' => $login,
        'pass'  => $pass,
        'email' => $email,
    ]);
    header("Location: manage_users.php");

    // $stmt = $conn->prepare("UPDATE users SET login=?, pass=?, email=?, admin=? WHERE id=?");
    // $stmt->bind_param("ssssi", $login, $pass, $email, $adminSts, $id);

    // if ($stmt->execute()) {
    //     header("Location: manage_users.php");
    // } else {
    //     echo "Błąd podczas aktualizacji danych użytkownika. <br> <input type='button' value='Powrót' onclick='window.history.back()'>";
    // }

    // $stmt->close();

} else {
    echo "Nieprawidłowe żądanie. <br> <input type='button' value='Powrót' onclick='window.history.back()'>";
}