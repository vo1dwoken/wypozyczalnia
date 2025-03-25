<?php include 'baza.php'; ?>

<?php
session_start();
if (!isset($_SESSION['adminLog_id'])) {
    header("Location: /startBiblioteka.php"); // Якщо не адмін, перенаправляємо на головну
    exit();
}
?>
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

        <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
            <h1>Autorzy</h1>
            <?php
            if (isset($_SESSION['adminLog_id'])) {
                echo '<a href="logout.php" class="admin-logout-button">Wyloguj się</a>';
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
        <p>© 2025 Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>