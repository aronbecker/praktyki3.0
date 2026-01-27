<?php
session_start();
session_destroy();
include_once 'class/DBManager.php';
include_once 'class/users.php';

// include 'dbmanager.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $login = $_POST['login'];
//     $pass = $_POST['pass'];

//     $stmt = $conn->prepare("SELECT * FROM users WHERE (BINARY login = ? OR email = ?) AND BINARY pass = ?");
//     $stmt->bind_param("sss", $login, $login, $pass);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         $user = $result->fetch_assoc();
//         session_start();
//         $_SESSION['login'] = $user['login'];
//         $_SESSION['user_id'] = $user['id'];
//         $_SESSION['admin'] = $user['admin'];
//         header("Location: index.php");
//         exit();
//     } else {
//         echo "Nieprawidłowa nazwa użytkownika lub hasło.";
//     }

//     $stmt->close();
//     $conn->close();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $users = new Users();

    $user = $users->authenticate($_POST['login'], $_POST['pass']);

    if ($user){
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login']   = $user['login'];
        $_SESSION['admin']   = $user['admin'];
        header("Location: index.php");
        exit();
    } else {
        echo "Nieprawidłowa nazwa użytkownika lub hasło.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style2.css">
</head>
<body>
    <h2>Logowanie</h2>
    <form method="POST" action="login.php">
        <div style="width: 30%;">
        <label for="login">Login lub email:</label><br>
        <input type="text" id="login" name="login" required><br>
        <label for="pass">Hasło:</label><br>
        <input type="password" id="pass" name="pass" required><br><br>
        <input type="submit" value="Zaloguj się">
        <input type="button" value="Nie masz konta? Zarejestruj się" onclick="window.location.href='register.php'">
        </form>
        </div>
</body>
</html>