<?php include 'bazaMM.php'; ?>
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
                                FROM ksiazka_MM k
                                JOIN autor_MM a ON k.autor_id = a.id
                                JOIN wydawca_MM w ON k.wydawca_id = w.id
                                JOIN tematyka_MM t ON k.tematyka_id = t.id");
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
        <p>© 2024 Mykhailenko. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
