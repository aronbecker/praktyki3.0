<?php
include_once 'class/DBManager.php';
include_once 'class/users.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $email = $_POST['email'];
    $adminSts = $_POST['admin'] ?? 0;
    $users = new Users();

    $user = $users->create($login,$pass,$email,$adminSts);
    if($user){
        echo "pomyślnie stworzono użytkownika";
    } else {
        echo "coś poszło nie tak";
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
    <h2>Rejestracja</h2>
    <div style="width: 30%;">
    <form method="post" action="">
        <label for="login">Login:</label><br>
        <input type="text" id="login" name="login" required><br>
        <label for="pass">Hasło:</label><br>
        <input type="password" id="pass" name="pass" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <?php
        session_start();
            if (array_key_exists('admin', $_SESSION)){
            if ($_SESSION['admin'] == 1) {
                echo '<label for="admin">Administrator:</label>';
                echo '<input type="checkbox" id="admin" name="admin" value="1"><br>';
            }
            }
        ?>
        <input type="submit" value="Zarejestruj się">
    </form>
    </div>
</body>
</html>