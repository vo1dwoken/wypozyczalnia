<?php include '../Main/baza.php'; ?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Main/style.css" />
    <title>Autorzy</title>
</head>

<body>
    <header>
        <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
            <h1>Autorzy</h1>
        </div>
        <!-- Контейнер для посилань на логін, реєстрацію та профіль -->
        <div class="auth-container">
            <?php
            session_start();
            if (isset($_SESSION['user_id'])) {
                // Якщо користувач залогінений, показуємо посилання на профіль
                echo '<a href="/User/logout.php" class="profile-link">Logout</a>';
            } else {
                // Якщо користувач не залогінений, показуємо посилання на логін та реєстрацію
                echo '<a href="/User/loginUser.php" class="auth-link">Logowanie</a> | <a href="/User/registerUser.php" class="auth-link">Rejestracja</a>';
            }
            ?>
        </div>
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
                    <th>Tytul</th>
                    <th>Rok wydania</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT ksiazka.id, autor.nazwisko, autor.imie, 
                    COALESCE(ksiazka.tytul, 'Brak książki') AS tytul, 
                    COALESCE(CAST(ksiazka.rok_wydania AS CHAR), '-') AS rok_wydania
                    FROM autor
                    LEFT JOIN ksiazka ON autor.id = ksiazka.autor_id
                    ORDER BY ksiazka.id ASC
                ";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Замінили htmlspecialchars() на перевірку на null
                        $id = $row['id'] ? htmlspecialchars($row['id']) : '';
                        $nazwisko = $row['nazwisko'] ? htmlspecialchars($row['nazwisko']) : '';
                        $imie = $row['imie'] ? htmlspecialchars($row['imie']) : '';
                        $tytul = $row['tytul'] ? htmlspecialchars($row['tytul']) : 'Brak książki';
                        $rok_wydania = $row['rok_wydania'] ? htmlspecialchars($row['rok_wydania']) : '-';

                        echo "<tr>
                                <td>$id</td>
                                <td>$nazwisko</td>
                                <td>$imie</td>
                                <td>$tytul</td>
                                <td>$rok_wydania</td>
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
        <p>© 2025 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>