<?php include 'bazaMM.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Wypozyczenia</title>
</head>
<body>
    <header>
        <h1>Wypozyczenia</h1>
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
        <h2>Lista wypozyczeń</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Czytelnik</th>
                    <th>Książka</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT w.id, c.nazwisko AS czytelnik, k.tytul AS ksiazka
                                        FROM wypozyczenie_MM w
                                        JOIN czytelnik_MM c ON w.czytelnik_id = c.id
                                        JOIN ksiazka_MM k ON w.ksiazka_id = k.id");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['czytelnik']) . "</td>
                                <td>" . htmlspecialchars($row['ksiazka']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Немає записів</td></tr>";
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
