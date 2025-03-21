<?php
include 'bazaMM.php';
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
    <title>Dodaj książkę</title>
</head>
<body>
    <header>
        <h1>Dodać książkę</h1>
        <nav>
            <a href="startBiblioteka.php">Strona główna</a>
            <a href="autorzyMM.php">Autorzy</a>
            <a href="ksiazkiMM.php">Książki</a>
            <a href="czytelnicyMM.php">Czytelnicy</a>
            <a href="wypozyczeniaMM.php">Wypożyczenia</a>
            <a href="addAutorMM.php">Dodać autora</a>
            <a href="addKsiazkaMM.php">Dodać książkę</a>
            <a href="addCzytelnikMM.php">Dodać czytelnika</a>
            <a href="addWypozycelnikaMM.php">Dodaj wypożyczenie</a>
            <a href="returnBookMM.php">Usuń wypożyczenie</a>
        
        </nav>
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
            $rok_wydania = intval($_POST['rok_wydania']);

            if ($rok_wydania <= 0) {
                die("Nieprawidłowy rok wydania.");
            }

            
            $autor_data = explode(" ", $autor, 2);
            if (count($autor_data) < 2) {
                die("Proszę podać zarówno imię, jak i nazwisko autora.");
            }
            $autor_imie = $autor_data[0];
            $autor_nazwisko = $autor_data[1];

            
            $stmt = $conn->prepare("SELECT id FROM autor_MM WHERE imie = ? AND nazwisko = ?");
            $stmt->bind_param("ss", $autor_imie, $autor_nazwisko);
            $stmt->execute();
            $stmt->bind_result($autor_id);
            if (!$stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO autor_MM (imie, nazwisko) VALUES (?, ?)");
                $stmt->bind_param("ss", $autor_imie, $autor_nazwisko);
                $stmt->execute();
                $autor_id = $stmt->insert_id;
            }
            $stmt->close();

            
            $stmt = $conn->prepare("SELECT id FROM wydawca_MM WHERE nazwa = ?");
            $stmt->bind_param("s", $wydawca);
            $stmt->execute();
            $stmt->bind_result($wydawca_id);
            if (!$stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO wydawca_MM (nazwa) VALUES (?)");
                $stmt->bind_param("s", $wydawca);
                $stmt->execute();
                $wydawca_id = $stmt->insert_id;
            }
            $stmt->close();

            
            $stmt = $conn->prepare("SELECT id FROM tematyka_MM WHERE nazwa = ?");
            $stmt->bind_param("s", $tematyka);
            $stmt->execute();
            $stmt->bind_result($tematyka_id);
            if (!$stmt->fetch()) {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO tematyka_MM (nazwa) VALUES (?)");
                $stmt->bind_param("s", $tematyka);
                $stmt->execute();
                $tematyka_id = $stmt->insert_id;
            }
            $stmt->close();

            
            $stmt = $conn->prepare("INSERT INTO ksiazka_MM (tytul, autor_id, wydawca_id, tematyka_id, rok_wydania) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("siiii", $tytul, $autor_id, $wydawca_id, $tematyka_id, $rok_wydania);

            if ($stmt->execute()) {
                echo "<p>Książka została pomyślnie dodana!</p>";
            } else {
                echo "<p>Wystąpił problem z dodaniem książki. Błąd: " . htmlspecialchars($conn->error) . "</p>";
            }
        }
        ?>
    </main>
    <footer>
        <p>© 2024 Mykhailenko. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
