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
            <a href="autorzy.php">Autorzy</a>
            <a href="wypozyczenia.php">Moje wypozyczenia</a>
        </nav>
    </header>
    <main>
        <h2>Lista wypozyczeń</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tytul</th>
                    <th>Rok Wydania</th>
                    <th>Autor</th>
                </tr>
            </thead>
            <tbody>
            <?php
                session_start();
                if (!isset($_SESSION['user_id'])) {
                    echo "<p>Proszę zalogować się na swoje konto.</p>";
                    exit;
                }

                $user_id = $_SESSION['user_id'];

                $query = "SELECT k.tytul, k.rok_wydania, CONCAT(a.imie, ' ', a.nazwisko) AS autor
                        FROM wypozyczenie w
                        JOIN ksiazka k ON w.ksiazka_id = k.id
                        JOIN autor a ON k.autor_id = a.id
                        WHERE w.czytelnik_id = ?";

                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        echo "<h2>Twoje wypożyczone książki</h2>";
                        echo "<table border='1'>
                                <tr>
                                    <th>Tytuł książki</th>
                                    <th>Rok wydania</th>
                                    <th>Autor</th>
                                </tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['tytul']) . "</td>
                                    <td>" . htmlspecialchars($row['rok_wydania']) . "</td>
                                    <td>" . htmlspecialchars($row['autor']) . "</td>
                                </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Nie wypożyczyłeś żadnej książki.</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p>Błąd zapytania.</p>";
                }
                $conn->close();
            ?>

            </tbody>
        </table>
    </main>
    <footer>
        <p>© 2024 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
