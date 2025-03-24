<?php
include 'baza.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dodać książkę</title>
</head>
<body>
    <header>
        
         <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
           <h1>Dodać książkę</h1>
        </div>
        <?php include 'navigation.php'; ?>
    </header>
    <main>
        <h2>Dodaj nową książkę:</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="tytul">Tytuł:</label>
            <input type="text" id="tytul" name="tytul" required>
            <br>
            <label for="autor">Autor (Imię i Nazwisko):</label>
            <input type="text" id="autor" name="autor" required>
            <br>
            <label for="wydawca">Wydawnictwo:</label>
            <input type="text" id="wydawca" name="wydawca" required>
            <br>
            <label for="tematyka">Tematyka:</label>
            <input type="text" id="tematyka" name="tematyka" required>
            <br>
            <label for="rok_wydania">Rok wydania:</label>
            <input type="number" id="rok_wydania" name="rok_wydania" required>
            <br>
            <label for="ilosc_calkowita">Ilość całkowita:</label>
            <input type="number" id="ilosc_calkowita" name="ilosc_calkowita" required>
            <br>
            <button type="submit" name="addBook">Dodać książkę</button>
        </form>

        <?php
        if (isset($_POST['addBook'])) {
            if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("Nieprawidłowy token CSRF!");
            }
        
            $tytul = htmlspecialchars($_POST['tytul']);
            $autor = htmlspecialchars($_POST['autor']);
            $wydawca = htmlspecialchars($_POST['wydawca']);
            $tematyka = htmlspecialchars($_POST['tematyka']);
            $rok_wydania = max(0, intval($_POST['rok_wydania']));
            $ilosc_calkowita = max(0, intval($_POST['ilosc_calkowita']));
            $ilosc_dostepnych = $ilosc_calkowita;
            
            if ($rok_wydania <= 0) {
                die("Nieprawidłowy rok wydania.");
            }
            
            $autor_data = explode(" ", $autor, 2);
            if (count($autor_data) < 2) {
                die("Proszę podać zarówno imię, jak i nazwisko autora.");
            }
            $autor_imie = $autor_data[0];
            $autor_nazwisko = $autor_data[1];
            
            $stmt = $conn->prepare("SELECT id FROM autor WHERE imie = ? AND nazwisko = ?");
            $stmt->bind_param("ss", $autor_imie, $autor_nazwisko);
            $stmt->execute();
            $stmt->bind_result($autor_id);
            if (!$stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO autor (imie, nazwisko) VALUES (?, ?)");
                $stmt->bind_param("ss", $autor_imie, $autor_nazwisko);
                $stmt->execute();
                $autor_id = $stmt->insert_id;
            }
            $stmt->close();
            
            $stmt = $conn->prepare("SELECT id FROM wydawca WHERE nazwa = ?");
            $stmt->bind_param("s", $wydawca);
            $stmt->execute();
            $stmt->bind_result($wydawca_id);
            if (!$stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO wydawca (nazwa) VALUES (?)");
                $stmt->bind_param("s", $wydawca);
                $stmt->execute();
                $wydawca_id = $stmt->insert_id;
            }
            $stmt->close();
            
            $stmt = $conn->prepare("SELECT id FROM tematyka WHERE nazwa = ?");
            $stmt->bind_param("s", $tematyka);
            $stmt->execute();
            $stmt->bind_result($tematyka_id);
            if (!$stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO tematyka (nazwa) VALUES (?)");
                $stmt->bind_param("s", $tematyka);
                $stmt->execute();
                $tematyka_id = $stmt->insert_id;
            }
            $stmt->close();
            
            $stmt = $conn->prepare("INSERT INTO ksiazka (tytul, autor_id, wydawca_id, tematyka_id, rok_wydania, ilosc_calkowita, ilosc_dostepnych) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siiiiii", $tytul, $autor_id, $wydawca_id, $tematyka_id, $rok_wydania, $ilosc_calkowita, $ilosc_dostepnych);
            
            if ($stmt->execute()) {
                echo "<p>Książka została pomyślnie dodana!</p>";
            } else {
                echo "<p>Wystąpił problem z dodaniem książki. Błąd: " . htmlspecialchars($conn->error) . "</p>";
            }
            $stmt->close();
        }
        ?>        
    </main>
    <footer>
        <p>© 2025 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
