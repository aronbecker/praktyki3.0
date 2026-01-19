<?php
include 'dbmanager.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $email = $_POST['email'];
    $adminSts = $_POST['admin'] ?? 0;

    $checkLoginStmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
    $checkLoginStmt->bind_param("s", $login);
    $checkLoginStmt->execute();
    $checkLoginStmt->store_result();

    if ($checkLoginStmt->num_rows > 0) {
        echo "Nazwa użytkownika już istnieje.";
    } else {
        $checkEmailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            echo "Email już jest zarejestrowany.";
            } else {
            $stmt = $conn->prepare("INSERT INTO users (login, pass, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $login, $pass, $email);
            if ($stmt->execute()) {
                echo "Rejestracja zakończona sukcesem.<input type='button' value='Powrót' onclick=\"window.history.back()\">";
            } else {
                echo "Błąd podczas rejestracji.";
        }
    }
    
}
    $checkLoginStmt->close();
    $checkEmailStmt->close();
    $stmt->close();
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
        $adminStmt = $conn->prepare("SELECT admin FROM users WHERE email = ?");
            $adminStmt->bind_param("s", $_SESSION['login']);
            $adminStmt->execute();
            $adminStmt->bind_result($isAdmin);
            $adminStmt->fetch();
            $adminStmt->close();
            if ($isAdmin == 1) {
                echo '<label for="admin">Administrator:</label>';
                echo '<input type="checkbox" id="admin" name="admin" value="1"><br>';
            }
        ?>
        <input type="submit" value="Zarejestruj się">
    </form>
    </div>
</body>
</html>