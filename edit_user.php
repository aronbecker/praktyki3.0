<?php
include 'dbmanager.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT login, pass, email, admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($login, $pass, $email, $adminSts);
    if ($stmt->fetch()) {
    } else {
        echo "Nie znaleziono użytkownika o podanym identyfikatorze.";
        exit();
    }
    $stmt->close();
} else {
    echo "Nieprawidłowe żądanie.";
    exit();;
}
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
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <label for="login">Login:</label><br>
                <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($login); ?>" required><br>
                <label for="pass">Hasło:</label><br>
                <input type="password" id="pass" name="pass" value="<?php echo htmlspecialchars($pass); ?>" required><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
                <label for="admin">Administrator:</label><br>
                <input type="checkbox" id="admin" name="admin" value="1" <?php echo $adminSts ? 'checked' : ''; ?>><br><br>
                <input type="submit" value="Zaktualizuj użytkownika">
            </form>
</div>
        </body>
        </html>