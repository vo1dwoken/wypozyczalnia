<?php include '../Main/baza.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Main/style.css">
    <title>Książki</title>
</head>
<body>
    <header>
        <h1>Książki</h1>
        <?php include 'navigation.php'; ?>
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
            <th>Ilosc dostepnych</th>
            <th>Dostepne</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT k.id, k.tytul, CONCAT(a.imie, ' ', a.nazwisko) AS autor, w.nazwa AS wydawca, t.nazwa AS tematyka, 
                                       k.rok_wydania, k.ilosc_dostepnych, k.dostepnosc
                                FROM ksiazka k
                                JOIN autor a ON k.autor_id = a.id
                                JOIN wydawca w ON k.wydawca_id = w.id
                                JOIN tematyka t ON k.tematyka_id = t.id");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dostepnosc = ($row['ilosc_dostepnych'] == 0) ? "Nie" : "Tak";
                echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['tytul']) . "</td>
                        <td>" . htmlspecialchars($row['autor']) . "</td>
                        <td>" . htmlspecialchars($row['wydawca']) . "</td>
                        <td>" . htmlspecialchars($row['tematyka']) . "</td>
                        <td>" . htmlspecialchars($row['rok_wydania']) . "</td>
                        <td>" . htmlspecialchars($row['ilosc_dostepnych']) . "</td>
                        <td>" . $dostepnosc . "</td>
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
