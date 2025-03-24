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
        
        <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
            <h1>Czytelnicy</h1>  
            <?php include 'buttonLogOutInReg.php'; ?>
        </div>
        <?php include 'navigation.php'; ?>
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
        <p>© 2025  Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
