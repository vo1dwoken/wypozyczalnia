<?php
include 'baza.php';
session_start();
if (!isset($_SESSION['adminLog_id'])) {
    header("Location: /startBiblioteka.php"); // Якщо не адмін, перенаправляємо на головну
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Отримуємо список авторів для вибору
$authors_result = $conn->query("SELECT id, CONCAT(imie, ' ', nazwisko) AS pelne_imie FROM autor");
$authors = [];
while ($row = $authors_result->fetch_assoc()) {
    $authors[] = $row;
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
            <?php
            if (isset($_SESSION['adminLog_id'])) {
                echo '<a href="logout.php" class="admin-logout-button">Wyloguj się</a>';
            }
            ?>
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

            <label for="autor_id">Autor:</label>
            <select id="autor_id" name="autor_id" required>
                <option value="">Wybierz autora</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?php echo $author['id']; ?>"><?php echo htmlspecialchars($author['pelne_imie']); ?></option>
                <?php endforeach; ?>
            </select>
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
            $autor_id = intval($_POST['autor_id']);
            $wydawca = htmlspecialchars($_POST['wydawca']);
            $tematyka = htmlspecialchars($_POST['tematyka']);
            $rok_wydania = max(0, intval($_POST['rok_wydania']));
            $ilosc_calkowita = max(0, intval($_POST['ilosc_calkowita']));
            $ilosc_dostepnych = $ilosc_calkowita;

            if ($rok_wydania <= 0) {
                die("Nieprawidłowy rok wydania.");
            }

            if ($autor_id <= 0) {
                die("Nieprawidłowy autor.");
            }

            // Додаємо видавця (якщо його немає)
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

            // Додаємо тематику (якщо її немає)
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

            // Додаємо книгу
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