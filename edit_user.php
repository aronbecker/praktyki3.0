<?php
include_once 'class/DBManager.php';
include_once 'class/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $user = new Users();
    $userData = $user->getById((int)$_GET['id']);
    $id = (int)$_GET['id'];
    
    if ($userData) {
        $login = $userData['login'];
        $pass = $userData['pass'];
        $email = $userData['email'];
        $adminSts = (bool)$userData['admin'];
    } else {
        echo "Nie znaleziono użytkownika o podanym identyfikatorze.";
        exit();
    }
} else {
    echo "Nieprawidłowe żądanie.";
    exit();
}
?>
        ?>
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edytuj użytkownika</title>
            <link rel="stylesheet" href="style/style2.css">
            <style>
                form>label, input{
                    display:block;
                    text-align:center;
                }
            </style>
        </head>
        <body>
            <div style='width: 30%;'>
            <h2>Edytuj użytkownika</h2>
            </div>
            <div style='width: 30%;'>
            <form method="POST" action="update_user.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="login">Login:</label><br>
                <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($login); ?>" required><br>
                <label for="pass">Hasło:</label><br>
                <input type="password" id="pass" name="pass" value="<?php echo htmlspecialchars($pass); ?>" required><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
                <!-- <label for="admin">Administrator:</label><br>
                <input type="checkbox" id="admin" name="admin" value="1" <?php echo $adminSts ? 'checked' : ''; ?>><br><br> -->
                <input type="submit" value="Zaktualizuj użytkownika">
            </form>
</div>
        </body>
        </html>