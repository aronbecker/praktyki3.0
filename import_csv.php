<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';
include_once 'class/users.php';
include_once 'class/kategorie.php';

session_start();

//pierwsze dwie cyfry pkd
function extractPKD2(string $pkd): ?string
        {
            if (preg_match('/^(\d{2})/', $pkd, $m)) {
                return $m[1];
            }
            return null;
        }

// Sprawdzenie czy użytkownik jest zalogowany i jest adminem
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$user = new Users();
if (!$user->isAdmin($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Sprawdzenie czy plik został przesłany
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile'];
    
    // Sprawdzenie błędów przesyłania
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Błąd przesyłania pliku!';
        header("Location: admin.php");
        exit();
    }
    
    // Sprawdzenie typu pliku
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if ($mime !== 'text/csv' && $mime !== 'text/plain') {
        $_SESSION['error'] = 'Plik musi być w formacie CSV!';
        header("Location: admin.php");
        exit();
    }
    
    // Otwarcie pliku do czytania
    if (($handle = fopen($file['tmp_name'], 'r')) === false) {
        $_SESSION['error'] = 'Nie można otworzyć pliku!';
        header("Location: admin.php");
        exit();
    }
    
    $firmy = new Firmy();
    $imported = 0;
    $updated = 0;
    $skipped = 0;
    $errors = [];
    
    // Czytanie linii po linii
    $rowNum = 0;
    $firstLine = fgets($handle);
    $delimiter = str_contains($firstLine, ';') ? ';' : ',';
    rewind($handle);

    while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {

        $rowNum++;

    //mapowanie nagłówków
        if (!isset($headerMap)) {
                $headerMap = [];

                foreach ($data as $index => $header) {
                    $header = trim($header);
                    if ($header !== '') {
                        $headerMap[$header] = $index;
                    }
                }

        continue;
}
        $nip = trim($data[$headerMap['Nip']] ?? '');
        $glownyPKD = trim($data[$headerMap['GlownyKodPkd']] ?? '');
        $pozostalePKD = trim($data[$headerMap['PozostaleKodyPkd']] ?? '');

        $rowData = [
        'Nip' => trim($data[$headerMap['Nip']] ?? '') ?: null,
        'Regon' => trim($data[$headerMap['Regon']] ?? '') ?: null,
        'NazwaPodmiotu' => trim($data[$headerMap['NazwaPodmiotu']] ?? '') ?: null,
        'Nazwisko' => trim($data[$headerMap['Nazwisko']] ?? '') ?: null,
        'Imie' => trim($data[$headerMap['Imie']] ?? '') ?: null,
        'Telefon' => trim($data[$headerMap['Telefon']] ?? '') ?: null,
        'Email' => trim($data[$headerMap['Email']] ?? '') ?: null,
        'AdresWWW' => trim($data[$headerMap['AdresWWW']] ?? '') ?: null,
        'KodPocztowy' => trim($data[$headerMap['KodPocztowy']] ?? '') ?: null,
        'Powiat' => trim($data[$headerMap['Powiat']] ?? '') ?: null,
        'Gmina' => trim($data[$headerMap['Gmina']] ?? '') ?: null,
        'Miejscowosc' => trim($data[$headerMap['Miejscowosc']] ?? '') ?: null,
        'Ulica' => trim($data[$headerMap['Ulica']] ?? '') ?: null,
        'NrBudynku' => trim($data[$headerMap['NrBudynku']] ?? '') ?: null,
        'NrLokalu' => trim($data[$headerMap['NrLokalu']] ?? '') ?: null,
];

        
        // Sprawdzenie czy NIP jest pusty
        if (empty($rowData['Nip'])) {
            $skipped++;
            $errors[] = "Wiersz $rowNum: NIP jest wymagany";
            continue;
        }
        
        // Sprawdzenie czy firma z takim NIP już istnieje
        $existingFirma = $firmy->getByNip($rowData['Nip']);
        
        try {
            if ($existingFirma) {
                // Aktualizacja istniejącej firmy
                $firmy->update($existingFirma['Lp'], $rowData);
                $updated++;
            } else {
                // Dodanie nowej firmy
                $firmy->create($rowData);
                $imported++;
            }

    

        $pkd2Set = [];

        /* Główny PKD */
        $pkd2 = extractPKD2($glownyPKD);
        if ($pkd2 !== null) {
            $pkd2Set[$pkd2] = true;
        }

        /* Pozostałe PKD */
        if (!empty($pozostalePKD)) {
            foreach (explode('$##$', $pozostalePKD) as $pkd) {
                $pkd2 = extractPKD2(trim($pkd));
                if ($pkd2 !== null) {
                    $pkd2Set[$pkd2] = true;
                }
            }
        }

        foreach (array_keys($pkd2Set) as $pkd2) {
            if(!is_null($nip)){
            $Kategorio = new Kategorie();
            $Kategorio->assignToCompanyByPKD($nip, $pkd2);
            }
        }

        } catch (Exception $e) {
            $skipped++;
            $errors[] = "Wiersz $rowNum (NIP: {$rowData['Nip']}): " . $e->getMessage();
        }
    }
    
    fclose($handle);
    
    // Przygotowanie wiadomości do wyświetlenia
    $message = "Import zakończony!\n";
    $message .= "- Dodano: $imported nowych firm\n";
    $message .= "- Zaktualizowano: $updated firm\n";
    $message .= "- Pominięto: $skipped wierszy";
    
    if (!empty($errors)) {
        $message .= "\n\nBłędy:\n" . implode("\n", array_slice($errors, 0, 10));
        if (count($errors) > 10) {
            $message .= "\n... i " . (count($errors) - 10) . " więcej błędów";
        }
    }
    
    $_SESSION['success'] = $message;
} else {
    $_SESSION['error'] = 'Nie wybrano pliku!';
}

header("Location: admin.php");
exit();
?>
