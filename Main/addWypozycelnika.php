<?php
include 'baza.php';

if (isset($_POST['addRental'])) {
    $czytelnik_id = intval($_POST['czytelnik_nazwisko']);
    $ksiazka_id = intval($_POST['ksiazka_tytul']);
    $ilosc_pozyczona = intval($_POST['ilosc']); // Liczba książek do wypożyczenia

    // Sprawdzamy, czy książka jest dostępna
    $stmt = $conn->prepare("SELECT ilosc_dostepnych FROM ksiazka WHERE id = ?");
    $stmt->bind_param("i", $ksiazka_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ksiazka = $result->fetch_assoc();

    if (!$ksiazka) {
        echo "<p>Błąd: Nie znaleziono książki o podanym ID.</p>";
    } elseif ($ksiazka['ilosc_dostepnych'] < $ilosc_pozyczona) {
        echo "<p>Błąd: Niewystarczająca liczba książek w bibliotece. Dostępne: " . htmlspecialchars($ksiazka['ilosc_dostepnych']) . ".</p>";
    } else {
        // Dodajemy wypożyczenie
        $stmt = $conn->prepare("INSERT INTO wypozyczenie (czytelnik_id, ksiazka_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $czytelnik_id, $ksiazka_id);
        if ($stmt->execute()) {
            // Aktualizujemy ilość dostępnych książek
            $stmt = $conn->prepare("UPDATE ksiazka SET ilosc_dostepnych = ilosc_dostepnych - ? WHERE id = ?");
            $stmt->bind_param("ii", $ilosc_pozyczona, $ksiazka_id);
            if ($stmt->execute()) {
                echo "<p>Wypożyczenie dodane pomyślnie! Pozostało książek: " . ($ksiazka['ilosc_dostepnych'] - $ilosc_pozyczona) . ".</p>";
            } else {
                echo "<p>Błąd: Nie udało się zaktualizować ilości książek.</p>";
            }
        } else {
            echo "<p>Błąd: Nie udało się dodać wypożyczenia.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dodać wypożyczenie</title>
</head>
<body>
    <header>
       
         <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
          <h1>Dodać wypożyczenie</h1>
            <?php include 'buttonLogOutInReg.php'; ?>
        </div>
        <?php include 'navigation.php'; ?>
    </header>
    <main>
        <h2>Dodaj nowe wypożyczenie:</h2>
        <form method="POST">
            <label for="czytelnik_nazwisko">Wybierz czytelnika:</label>
            <select id="czytelnik_nazwisko" name="czytelnik_nazwisko" required>
                <option value="">-- Wybierz czytelnika --</option>
                <?php
                $result = $conn->query("SELECT id, nazwisko FROM czytelnik");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nazwisko']) . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="ksiazka_tytul">Wybierz książkę:</label>
            <select id="ksiazka_tytul" name="ksiazka_tytul" required>
                <option value="">-- Wybierz książkę --</option>
                <?php
                $result = $conn->query("SELECT id, tytul FROM ksiazka");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['tytul']) . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="ilosc">Podaj ilość:</label>
            <input type="number" id="ilosc" name="ilosc" min="1" required>
            <br>
            <button type="submit" name="addRental">Dodaj wypożyczenie</button>
        </form>
    </main>
    <footer>
        <p>© 2025 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
