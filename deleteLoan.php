<?php
include 'baza.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Brak dostępu.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM wypozyczenie WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Wypożyczenie usunięte! <a href='adminPanel.php'>Powrót</a>";
    } else {
        echo "Błąd przy usuwaniu.";
    }
}
?>