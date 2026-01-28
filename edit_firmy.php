<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lp'])) {
    $lp = (int)$_POST['lp'];
    $firmy = new Firmy();
    
    $data = [
        'Nip' => $_POST['NIP'],
        'Regon' => $_POST['REGON'],
        'NazwaPodmiotu' => $_POST['nazwapodmiotu'],
        'Nazwisko' => $_POST['nazwisko'],
        'Imie' => $_POST['imie'],
        'Telefon' => $_POST['telefon'],
        'Email' => $_POST['email'],
        'AdresWWW' => $_POST['adreswww'],
        'KodPocztowy' => $_POST['kodpocztowy'],
        'Powiat' => $_POST['powiat'],
        'Gmina' => $_POST['gmina'],
        'Miejscowosc' => $_POST['miejscowosc'],
        'Ulica' => $_POST['ulica'],
        'NrBudynku' => $_POST['nrbudynku'],
        'NrLokalu' => $_POST['nrlokalu']
    ];
    
    $firmy->update($lp, $data);
    header("Location: admin.php");
    exit();
} else {
    echo "Nieprawidłowe żądanie. <br> <input type='button' value='Powrót' onclick='window.history.back()'>";
}
?>