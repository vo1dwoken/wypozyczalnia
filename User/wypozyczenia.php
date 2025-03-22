<?php include '../Main/baza.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Main/style.css">
    <title>Wypozyczenia</title>
</head>
<body>
    <header>
        <h1>Wypozyczenia</h1>
        <nav>
            <a href="../startBiblioteka.html">Strona główna</a>
            <a href="ksiazki.php">Książki</a>
            <a href="wypozyczenia.php">Wypozyczenia</a>
            <a href="autorzy.php">Autorzy</a>
           
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
                                        FROM wypozyczenie w
                                        JOIN czytelnik c ON w.czytelnik_id = c.id
                                        JOIN ksiazka k ON w.ksiazka_id = k.id");
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
        <p>© 2024 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
