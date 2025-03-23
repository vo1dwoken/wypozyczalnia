<?php include 'baza.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Autorzy</title>
</head>
<body>
    <header>
        <h1>Autorzy</h1>
        <?php include 'buttonLogOutInReg.php'; ?>
        <?php include 'navigation.php'; ?>
    </header>
    <main>
        <h2>Lista autorów</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwisko</th>
                    <th>Imię</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM autor");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['nazwisko']) . "</td>
                                <td>" . htmlspecialchars($row['imie']) . "</td>
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
