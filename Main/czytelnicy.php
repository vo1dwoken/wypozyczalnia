<?php include 'baza.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Czytelnicy</title>
</head>
<body>
    <header>
        <h1>Czytelnicy</h1>
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
        <h2>Lista czytelników</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwisko</th>
                    <th>Ulica</th>
                    <th>Numer ulicy</th>
                    <th>Numer mieszkania</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT c.id, c.nazwisko, u.nazwa AS ulica, c.ulica_numer, c.mieszkanie_numer
                                        FROM czytelnik c
                                        JOIN ulica u ON c.ulica_id = u.id");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['nazwisko']) . "</td>
                                <td>" . htmlspecialchars($row['ulica']) . "</td>
                                <td>" . htmlspecialchars($row['ulica_numer']) . "</td>
                                <td>" . htmlspecialchars($row['mieszkanie_numer']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Немає записів</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>© 2024  Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
