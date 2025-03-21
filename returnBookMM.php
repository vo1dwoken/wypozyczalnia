<?php
include 'bazaMM.php';
session_start();


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if (isset($_POST['returnBook'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Nieprawidłowy token CSRF!");
    }

    $wypozyczenie_id = intval($_POST['wypozyczenie_id']);
    $ksiazka_id = intval($_POST['ksiazka_id']);

    $stmt = $conn->prepare("DELETE FROM wypozyczenie_MM WHERE id = ?");
    $stmt->bind_param("i", $wypozyczenie_id);

    if ($stmt->execute()) {
       
        $updateStmt = $conn->prepare("UPDATE ksiazka_MM SET ilosc = ilosc + 1 WHERE id = ?");
        $updateStmt->bind_param("i", $ksiazka_id);

        if ($updateStmt->execute()) {
            echo "<p>Książka została pomyślnie zwrócona, a ilość została zaktualizowana!</p>";
        } else {
            echo "<p>Błąd przy aktualizacji ilości książek: " . htmlspecialchars($conn->error) . "</p>";
        }
    } else {
        echo "<p>Błąd przy usuwaniu wypożyczenia: " . htmlspecialchars($conn->error) . "</p>";
    }
}


$readers = $conn->query("SELECT id, nazwisko FROM czytelnik_MM ORDER BY nazwisko");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Zwróć książkę</title>
</head>
<body>
     <header>
        <h1>Zwrot książki</h1>
        <nav>
            <a href="startBiblioteka.php">Strona główna</a>
            <a href="autorzyMM.php">Autorzy</a>
            <a href="ksiazkiMM.php">Książki</a>
            <a href="czytelnicyMM.php">Czytelnicy</a>
            <a href="wypozyczeniaMM.php">Wypożyczenia</a>
            <a href="addAutorMM.php">Dodaj autora</a>
            <a href="addKsiazkaMM.php">Dodaj książkę</a>
            <a href="addCzytelnikMM.php">Dodaj czytelnika</a>
            <a href="addWypozycelnikaMM.php">Dodaj wypożyczenie</a>
            <a href="returnBookMM.php">Usuń wypożyczenie</a>
        </nav>
    </header>
    <main>
        <h2>Wybierz czytelnika:</h2>
        <form method="GET">
            <label for="czytelnik_id">Wybierz czytelnika:</label>
            <select id="czytelnik_id" name="czytelnik_id" required>
                <option value="">-- Wybierz czytelnika --</option>
                <?php
                while ($reader = $readers->fetch_assoc()) {
                    $selected = isset($_GET['czytelnik_id']) && $_GET['czytelnik_id'] == $reader['id'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($reader['id']) . "' $selected>" . htmlspecialchars($reader['nazwisko']) . "</option>";
                }
                ?>
            </select>
            <br>
            <button type="submit">Pokaż książki</button>
        </form>

        <?php
        if (isset($_GET['czytelnik_id'])) {
            $czytelnik_id = intval($_GET['czytelnik_id']);

            $stmt = $conn->prepare("SELECT wypozyczenie_MM.id AS wypozyczenie_id, ksiazka_MM.tytul, ksiazka_MM.id AS ksiazka_id
                                    FROM wypozyczenie_MM
                                    JOIN ksiazka_MM ON wypozyczenie_MM.ksiazka_id = ksiazka_MM.id
                                    WHERE wypozyczenie_MM.czytelnik_id = ?");
            $stmt->bind_param("i", $czytelnik_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>Książki wypożyczone przez czytelnika:</h2>";
                echo "<table>";
                echo "<thead>
                        <tr>
                            <th>#</th>
                            <th>Tytuł</th>
                            <th>Akcja</th>
                        </tr>
                      </thead>";
                echo "<tbody>";

                while ($wypozyczenie = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($wypozyczenie['wypozyczenie_id']) . "</td>
                            <td>" . htmlspecialchars($wypozyczenie['tytul']) . "</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>
                                    <input type='hidden' name='wypozyczenie_id' value='" . htmlspecialchars($wypozyczenie['wypozyczenie_id']) . "'>
                                    <input type='hidden' name='ksiazka_id' value='" . htmlspecialchars($wypozyczenie['ksiazka_id']) . "'>
                                    <button type='submit' name='returnBook'>Zwróć książkę</button>
                                </form>
                            </td>
                          </tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>Brak wypożyczonych książek.</p>";
            }
        }
        ?>
    </main>
    <footer>
        <p>© 2024 Mykhailenko. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
