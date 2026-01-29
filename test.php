<?php
include_once 'class/kategorie.php';
$nip = 42;
$pkd = '01';
$kategorio = new Kategorie();
$kategorio->assignToCompanyByPKD($nip, $pkd);
?>