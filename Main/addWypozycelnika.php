<?php
include 'baza.php';

if (isset($_POST['addRental'])) {
    $czytelnik_id = intval($_POST['czytelnik_nazwisko']);
    $ksiazka_id = intval($_POST['ksiazka_tytul']);
    $ilosc_pozyczona = intval($_POST['ilosc']); // Кількість книг, які хочете позичити.

    // Перевіряємо, чи є така книга в наявності.
    $stmt = $conn->prepare("SELECT ilosc FROM ksiazka WHERE id = ?");
    $stmt->bind_param("i", $ksiazka_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ksiazka = $result->fetch_assoc();

    if (!$ksiazka) {
        echo "<p>Błąd: Nie znaleziono książki o podanym ID.</p>";
    } elseif ($ksiazka['ilosc'] < $ilosc_pozyczona) {
        echo "<p>Błąd: Niewystarczająca liczba książek w bibliotece. Dostępne: " . htmlspecialchars($ksiazka['ilosc']) . ".</p>";
    } else {
        // Додаємо позичання.
        $stmt = $conn->prepare("INSERT INTO wypozyczenie (czytelnik_id, ksiazka_id, ilosc) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $czytelnik_id, $ksiazka_id, $ilosc_pozyczona);
        if ($stmt->execute()) {
            // Оновлюємо кількість книг у базі даних.
            $stmt = $conn->prepare("UPDATE ksiazka SET ilosc = ilosc - ? WHERE id = ?");
            $stmt->bind_param("ii", $ilosc_pozyczona, $ksiazka_id);
            if ($stmt->execute()) {
                echo "<p>Wypożyczenie dodane pomyślnie! Pozostało książek: " . ($ksiazka['ilosc'] - $ilosc_pozyczona) . ".</p>";
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
    <title>Dodaj wypożyczenie</title>
</head>
<body>
    <header>
        <h1>Dodaj wypożyczenie</h1>
        <nav>
            <a href="startBiblioteka.php">Strona główna</a>
            <a href="autorzy.php">Autorzy</a>
            <a href="ksiazki.php">Książki</a>
            <a href="czytelnicy.php">Czytelnicy</a>
            <a href="wypozyczenia.php">Wypożyczenia</a>
            <a href="addAutor.php">Dodaj autora</a>
            <a href="addKsiazka.php">Dodaj książkę</a>
            <a href="addCzytelnik.php">Dodaj czytelnika</a>
            <a href="addWypozycelnika.php">Dodaj wypożyczenie</a>
            <a href="returnBook.php">Usuń wypożyczenie</a>
        </nav>
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
        <p>© 2024 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
