<?php
include 'dbmanager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $pass = $_POST['pass'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE (login = ? OR email = ?) AND pass = ?");
    $stmt->bind_param("sss", $login, $login, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['login'] = $login;
        header("Location: index.php");
        exit();
    } else {
        echo "Nieprawidłowa nazwa użytkownika lub hasło.";
    }

    $stmt->close();
    $conn->close();
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