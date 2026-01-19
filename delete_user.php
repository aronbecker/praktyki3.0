<?php
include 'dbmanager.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Błąd: " . $stmt->error;
        echo "<br><input type='button' value='Powrót' onclick='window.history.back()'>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
    echo "<br><input type='button' value='Powrót' onclick='window.history.back()'>";
}