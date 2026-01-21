<?php
include 'dbmanager.php';
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lp'])) {
    $lp = $_GET['lp'];
    $stmt = $conn->prepare("SELECT nip, regon, nazwapodmiotu, nazwisko, imie, telefon, email, adreswww, kodpocztowy, powiat, gmina, miejscowosc, ulica, nrbudynku, nrlokalu FROM firmy WHERE lp = ?");
    $stmt->bind_param("i", $lp);
    $stmt->execute();
    $stmt->bind_result($nip, $regon, $nazwapodmiotu, $nazwisko, $imie, $telefon, $email, $adreswww, $kodpocztowy, $powiat, $gmina, $miejscowosc, $ulica, $nrbudynku, $nrlokalu);
    if ($stmt->fetch()) {
        // Dane firmy zostały pobrane pomyślnie
    } else {
        echo "Nie znaleziono firmy o podanym identyfikatorze.";
        exit();;
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lp = $_POST['lp'];
    $nip = $_POST['NIP'];
    $regon = $_POST['REGON'];
    $nazwapodmiotu = $_POST['nazwa'];
    $nazwisko = $_POST['nazwisko'];
    $imie = $_POST['imie'];
    $telefon = $_POST['nr_telefonu'];
    $email = $_POST['email'];
    $adreswww = $_POST['adreswww'];
    $kodpocztowy = $_POST['kodpocztowy'];
    $powiat = $_POST['powiat'];
    $gmina = $_POST['gmina'];
    $miejscowosc = $_POST['miejscowosc'];
    $ulica = $_POST['ulica'];
    $nrbudynku = $_POST['nrbudynku'];
    $nrlokalu = $_POST['nrlokalu'];
    $stmt = $conn->prepare("UPDATE firmy SET nip = ?, regon = ?, nazwapodmiotu = ?, nazwisko = ?, imie = ?, telefon = ?, email = ?, adreswww = ?, kodpocztowy = ?, powiat = ?, gmina = ?, miejscowosc = ?, ulica = ?, nrbudynku = ?, nrlokalu = ? WHERE lp = ?");
    $stmt->bind_param("sssssssssssssssi", $nip, $regon, $nazwapodmiotu, $nazwisko, $imie, $telefon, $email, $adreswww, $kodpocztowy, $powiat, $gmina, $miejscowosc, $ulica, $nrbudynku, $nrlokalu, $lp);
    if ($stmt->execute()) {
        echo "Dane firmy zostały zaktualizowane pomyślnie.";
        header("Location: admin.php");
        exit();
    } else {
        echo "Błąd: " . $stmt->error;
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
            <title>Edytuj Firmę</title>
            <style>
                body{
                    background-color: lightgrey;
                    font-family: Arial, Helvetica, sans-serif;
                }
                h1,h2{
                    text-align: center;
                }
                input{ 
                    margin-left: auto;
                    margin-right: auto;
                    display: block;
                    margin-top: 10px;
                    padding: 10px 20px;
                    font-size: 16px;
                    background-color: white;
                    color: black;
                    border: 2px solid black;
                    border-radius: 5px;
                }
                input[type="button"], input[type="submit"]{
                    margin-left: auto;
                    margin-right: auto;
                    margin-top: 10px;
                    padding: 10px 20px;
                    font-size: 16px;
                    background-color: white;
                    color: black;
                    border: 2px solid black;
                    border-radius: 5px;
                    cursor: pointer;
                }
                label{
                    display: block;
                    text-align: center;
                    margin-top: 10px;
                }

                * {
  box-sizing: border-box;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

button {
  cursor: pointer;
  border: none;
  font-size: 14px;
}

/* PRZYCISK OTWIERAJĄCY */
#addCategoryBtn {
  padding: 10px 18px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff;
  border-radius: 8px;
  font-weight: 600;
  box-shadow: 0 10px 20px rgba(99,102,241,0.25);
  transition: transform .15s ease, box-shadow .15s ease;
}

.fajny_przycisk {
      padding: 10px 18px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff;
  border-radius: 8px;
  font-weight: 600;
  box-shadow: 0 10px 20px rgba(99,102,241,0.25);
  transition: transform .15s ease, box-shadow .15s ease;
}

#addCategoryBtn:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 25px rgba(99,102,241,0.35);
}

/* MODAL */
.modal {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.55);
  backdrop-filter: blur(6px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal.hidden {
  display: none;
}

/* KARTA MODALA */
.modal-content {
  width: 100%;
  max-width: 360px;
  background: rgba(255,255,255,0.95);
  border-radius: 14px;
  padding: 24px;
  box-shadow: 0 30px 60px rgba(0,0,0,0.25);
  animation: modalIn .25s ease-out;
}

@keyframes modalIn {
  from {
    transform: translateY(15px) scale(.95);
    opacity: 0;
  }
  to {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

/* NAGŁÓWEK */
.modal-content h3 {
  margin: 0 0 16px;
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
}

/* FORM */
form label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #475569;
  margin-bottom: 6px;
}

/* SELECT */
form select {
  width: 100%;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #cbd5f5;
  background: #f8fafc;
  font-size: 14px;
  outline: none;
  transition: border-color .15s ease, box-shadow .15s ease;
}

form select:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
}

/* ACTIONS */
.actions {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  margin-top: 20px;
}

/* BUTTONS */
.actions button {
  flex: 1;
  padding: 10px;
  border-radius: 8px;
  font-weight: 600;
  transition: all .15s ease;
}

/* ZAPISZ */
.actions button[type="submit"] {
  background: #6366f1;
  color: white;
}

.actions button[type="submit"]:hover {
  background: #4f46e5;
}

/* ANULUJ */
#closeModal {
  background: #e5e7eb;
  color: #334155;
}

#closeModal:hover {
  background: #d1d5db;
}


                .hidden {
                    display: none!important;
                }

                .modal {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                }

                .modal-content {
                background: #fff;
                padding: 20px;
                border-radius: 6px;
                width: 300px;
                }

                .actions {
                margin-top: 15px;
                display: flex;
                justify-content: space-between;
                }
            </style>
        </head>
        <body>
            
            <h1>Edytuj Firmę</h1>
            <div style="
            width: 52%;
            padding: 10px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            height: auto;
            border: 2px solid black;
            box-shadow: 10px 10px 5px grey;">
            <form method="POST" action="edit_firmy.php">
                <div style="float: left; margin-right: 20px;">
                <input type="hidden" name="lp" value="<?php echo htmlspecialchars($lp); ?>">
                <label for="NIP">NIP:</label><br>
                <input type="text" id="NIP" name="NIP" value="<?php echo htmlspecialchars($nip); ?>"><br>
                <label for="REGON">REGON:</label><br>
                <input type="text" id="REGON" name="REGON" value="<?php echo htmlspecialchars($regon); ?>"><br>
                <label for="nazwa">Nazwa:</label><br>
                <input type="text" id="nazwapodmiotu" name="nazwapodmiotu" value="<?php echo htmlspecialchars($nazwapodmiotu); ?>" required><br>
                <label for="imie">Imię:</label><br>
                <input type="text" id="imie" name="imie" value="<?php echo htmlspecialchars($imie); ?>"><br>
                <label for="nazwisko">Nazwisko:</label><br>
                <input type="text" id="nazwisko" name="nazwisko" value="<?php echo htmlspecialchars($nazwisko); ?>"><br>
</div><div style="float: left; margin-right: 20px;">
                <label for="nr_telefonu">Numer Telefonu:</label><br>
                <input type="text" id="nr_telefonu" name="nr_telefonu" value="<?php echo htmlspecialchars($telefon); ?>"><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
                <label for="adreswww">Adres WWW:</label><br>
                <input type="text" id="adreswww" name="adreswww" value="<?php echo htmlspecialchars($adreswww); ?>"><br>
                <label for="kodpocztowy">Kod Pocztowy:</label><br>
                <input type="text" id="kodpocztowy" name="kodpocztowy" value="<?php echo htmlspecialchars($kodpocztowy); ?>"><br>
                <label for="powiat">Powiat:</label><br>
                <input type="text" id="powiat" name="powiat" value="<?php echo htmlspecialchars($powiat); ?>"><br>
</div><div style="float: left;">
                <label for="gmina">Gmina:</label><br>
                <input type="text" id="gmina" name="gmina" value="<?php echo htmlspecialchars($gmina); ?>"><br>
                <label for="miejscowosc">Miejscowość:</label><br>
                <input type="text" id="miejscowosc" name="miejscowosc" value="<?php echo htmlspecialchars($miejscowosc); ?>"><br>
                <label for="ulica">Ulica:</label><br>
                <input type="text" id="ulica" name= "ulica"value="<?php echo htmlspecialchars($ulica); ?>"><br>
                <label for="nrbudynku">Numer Budynku:</label><br>
                <input type="text" id="nrbudynku" name="nrbudynku" value="<?php echo htmlspecialchars($nrbudynku); ?>"><br>
                <label for="nrlokalu">Numer Lokalu:</label><br>
                <input type="text" id="nrlokalu" name="nrlokalu" value="<?php echo htmlspecialchars($nrlokalu); ?>"><br>
</div><div style="display: block; clear: both; margin-top: 20px;">
                <input type="button" value="Dodaj kategorie" onclick="kategorieOkienko()"><br>
                <input type="submit" value="Zatwierdź">
</div>
            </form>
            </div>
            <input type="button" value="Powrót do panelu administracyjnego" onclick="window.location.href='admin.php'" class="fajny_przycisk">
            <button id="addCategoryBtn">DODAJ KATEGORIE</button>
            <script>
                function kategorieOkienko() {
                    var kategoria = prompt("Wpisz kategorię:");
                    if (kategoria != null && kategoria.trim() !== "") {
                        fetch('add_kategoria.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'lp=' + encodeURIComponent(<?php echo $lp; ?>) + '&kategoria=' + encodeURIComponent(kategoria)
                        })
                        .then(response => response.text())
                        .then(data => alert(data))
                        .catch(error => alert('Błąd: ' + error));
                    }
                }
            </script>

            <div id="categoryModal" class="modal hidden">
            <div class="modal-content">
                <h3>Dodaj kategorię</h3>

                <form id="categoryForm" >
                <label for="categorySelect">Wybierz kategorię:</label>
                <select id="categorySelect" required>
                    <?php ?>
                    <option value="1">Usługi budowlane</option>
                    <option value="2">Usługi ksiegowe</option>
                </select>

                <div class="actions">
                    <button type="submit">Zapisz</button>
                    <button type="button" id="closeModal">Anuluj</button>
                </div>
                </form>
            </div>
            </div>
            <script>
                const addCategoryBtn = document.getElementById('addCategoryBtn');
                const modal = document.getElementById('categoryModal');
                const closeModalBtn = document.getElementById('closeModal');
                const categorySelect = document.getElementById('categorySelect');
                const categoryForm = document.getElementById('categoryForm');

                // Otwórz popup
                addCategoryBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                });

                // Zamknij popup
                closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                });


                // Obsługa formularza
                categoryForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const selectedCategoryId = categorySelect.value;

                if (!selectedCategoryId) return;

                console.log('Wybrana kategoria ID:', selectedCategoryId);

                // tutaj możesz:
                // - wysłać dane do backendu
                // - dodać kategorię do widoku
                // - zamknąć modal

                modal.classList.add('hidden');
                });

                </script>
        </body>
        </html>