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
    <title>Dodaj autora</title>
</head>
<body>
    <header>
        <h1>Dodać autora</h1>
        <nav>
            <a href="startBiblioteka.php">Strona główna</a>
            <a href="autorzyMM.php">Autorzy</a>
            <a href="ksiazkiMM.php">Książki</a>
            <a href="czytelnicyMM.php">Czytelnicy</a>
            <a href="wypozyczeniaMM.php">Wypozyczenia</a>
            <a href="addAutorMM.php">Dodać autora</a>
            <a href="addKsiazkaMM.php">Dodać książkę</a>
            <a href="addCzytelnikMM.php">Dodać czytelnika</a>
            <a href="addWypozycelnikaMM.php">Dodaj wypożyczenie</a>
            <a href="returnBookMM.php">Usuń wypożyczenie</a>
        </nav>
    </header>
    <main>
        <h2>Dodaj nowego autora:</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="imie">Imię:</label>
            <input type="text" id="imie" name="imie" required>
            <br>
            <label for="nazwisko">Nazwisko:</label>
            <input type="text" id="nazwisko" name="nazwisko" required>
            <br>
            <button type="submit" name="addAuthor">Dodaj autora</button>
        </form>

        <?php
        if (isset($_POST['addAuthor'])) {
            if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("Nieprawidłowy token CSRF!");
            }

            $imie = htmlspecialchars($_POST['imie']);
            $nazwisko = htmlspecialchars($_POST['nazwisko']);

           
            if (!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s-]+$/u", $imie)) {
                die("Nieprawidłowe imię.");
            }
            if (!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s-]+$/u", $nazwisko)) {
                die("Nieprawidłowe nazwisko.");
            }

            $stmt = $conn->prepare("INSERT INTO autor_MM (imie, nazwisko) VALUES (?, ?)");
            $stmt->bind_param("ss", $imie, $nazwisko);

            if ($stmt->execute()) {
                echo "<p>Autor został pomyślnie dodany!</p>";
            } else {
                error_log("Błąd: " . $conn->error);
                echo "<p>Wystąpił problem z dodaniem autora.</p>";
            }
        }
        ?>
    </main>
    <footer>
        <p>© 2024 Mykhailenko. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
