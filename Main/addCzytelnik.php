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
    <title>Dodać czytelnika</title>
</head>
<body>
    <header>
        
        <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
          <h1>Dodać czytelnika</h1>
        </div>
        <?php include 'navigation.php'; ?>
    </header>
    <main>
        <h2>Dodaj nowego czytelnika:</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="nazwisko">Nazwisko:</label>
            <input type="text" id="nazwisko" name="nazwisko" required>
            <br>
            <label for="ulica">Nazwa ulicy:</label>
            <input type="text" id="ulica" name="ulica" required>
            <br>
            <label for="ulica_numer">Numer ulicy:</label>
            <input type="number" id="ulica_numer" name="ulica_numer" required>
            <br>
            <label for="mieszkanie_numer">Numer mieszkania:</label>
            <input type="number" id="mieszkanie_numer" name="mieszkanie_numer">
            <br>
            <button type="submit" name="addReader">Dodać czytelnika</button>
        </form>

        <?php
        if (isset($_POST['addReader'])) {
            if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("Nieprawidłowy token CSRF!");
            }

            $nazwisko = htmlspecialchars($_POST['nazwisko']);
            $ulica = htmlspecialchars($_POST['ulica']);
            $ulica_numer = intval($_POST['ulica_numer']);
            $mieszkanie_numer = isset($_POST['mieszkanie_numer']) ? intval($_POST['mieszkanie_numer']) : null;

            if (!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s-]+$/u", $nazwisko)) {
                die("Nieprawidłowe nazwisko.");
            }

            if (!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s-]+$/u", $ulica)) {
                die("Nieprawidłowa nazwa ulicy.");
            }

            if ($ulica_numer <= 0) {
                die("Numer ulicy musi być dodatni.");
            }

            // Перевірка чи вулиця вже існує
            $stmt = $conn->prepare("SELECT id FROM ulica WHERE nazwa = ?");
            $stmt->bind_param("s", $ulica);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $ulica_id = $row['id'];
            } else {
                // Додавання нової вулиці
                $stmt = $conn->prepare("INSERT INTO ulica (nazwa) VALUES (?)");
                $stmt->bind_param("s", $ulica);
                if (!$stmt->execute()) {
                    die("Błąd dodawania nowej ulicy: " . $conn->error);
                }
                $ulica_id = $stmt->insert_id;
            }

            // Додавання читача
            $stmt = $conn->prepare("INSERT INTO czytelnik (nazwisko, ulica_id, ulica_numer, mieszkanie_numer) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siii", $nazwisko, $ulica_id, $ulica_numer, $mieszkanie_numer);

            if ($stmt->execute()) {
                echo "<p>Czytelnik został pomyślnie dodany!</p>";
            } else {
                error_log("Błąd: " . $conn->error);
                echo "<p>Wystąpił problem z dodaniem czytelnika.</p>";
            }
        }
        ?>
    </main>
    <footer>
        <p>© 2025 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
