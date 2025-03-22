<?php include 'baza.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Książki</title>
</head>
<body>
    <header>
        <h1>Książki</h1>
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
        <h2>Lista książek</h2>
        <table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nazwa</th>
            <th>Autor</th>
            <th>Wydawca</th>
            <th>Tematy</th>
            <th>Rok publikacji</th>
            <th>Ilość</th>
            <th>Dostępna</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT k.id, k.tytul, a.nazwisko AS autor, w.nazwa AS wydawca, t.nazwa AS tematyka, 
                                       k.rok_wydania, k.ilosc, k.dostepna
                                FROM ksiazka k
                                JOIN autor a ON k.autor_id = a.id
                                JOIN wydawca w ON k.wydawca_id = w.id
                                JOIN tematyka t ON k.tematyka_id = t.id");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['tytul']) . "</td>
                        <td>" . htmlspecialchars($row['autor']) . "</td>
                        <td>" . htmlspecialchars($row['wydawca']) . "</td>
                        <td>" . htmlspecialchars($row['tematyka']) . "</td>
                        <td>" . htmlspecialchars($row['rok_wydania']) . "</td>
                        <td>" . htmlspecialchars($row['ilosc']) . "</td>
                        <td>" . ($row['dostepna'] ? "Tak" : "Nie") . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Немає записів</td></tr>";
        }
        ?>
    </tbody>
</table>

    </main>
    <footer>
        <p>© 2024 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
