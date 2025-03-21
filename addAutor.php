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
    <title>Dodaj autora</title>
</head>
<body>
    <header>
        <h1>Dodać autora</h1>
        <nav>
            <a href="startBiblioteka.php">Strona główna</a>
            <a href="autorzy.php">Autorzy</a>
            <a href="ksiazki.php">Książki</a>
            <a href="czytelnicy.php">Czytelnicy</a>
            <a href="wypozyczenia.php">Wypozyczenia</a>
            <a href="addAutor.php">Dodać autora</a>
            <a href="addKsiazka.php">Dodać książkę</a>
            <a href="addCzytelnik.php">Dodać czytelnika</a>
            <a href="addWypozycelnika.php">Dodaj wypożyczenie</a>
            <a href="returnBook.php">Usuń wypożyczenie</a>
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

            $stmt = $conn->prepare("INSERT INTO autor (imie, nazwisko) VALUES (?, ?)");
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
        <p>© 2024 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
