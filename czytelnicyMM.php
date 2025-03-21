<?php include 'bazaMM.php'; ?>
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
                                        FROM czytelnik_MM c
                                        JOIN ulica_MM u ON c.ulica_id = u.id");
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
        <p>© 2024 Mykhailenko. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
