<?php
include 'baza.php';
session_start();

if (!isset($_SESSION['adminLog_id'])) {
    header("Location: login.php");
    exit();
}

echo "<h1>Panel administratora</h1>";

// Відображаємо всі позичення
$result = $conn->query("SELECT * FROM wypozyczenie_widok");
echo "<h2>Lista wypożyczeń:</h2>";
echo "<table border='1'><tr><th>ID</th><th>Czytelnik</th><th>Książka</th><th>Akcje</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['czytelnik']}</td>
            <td>{$row['ksiazka']}</td>
            <td>
                <a href='deleteLoan.php?id={$row['id']}'>Usuń</a>
            </td>
          </tr>";
}
echo "</table>";

?>
<a href="logout.php">Wyloguj się</a>