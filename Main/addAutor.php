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
    <title>Dodać autora</title>
</head>
<body>
    <header>
        
         <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
            <h1>Dodać autora</h1>
            <?php include 'buttonLogOutInReg.php'; ?>
        </div>
        <?php include 'navigation.php'; ?>
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
        <p>© 2025 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
