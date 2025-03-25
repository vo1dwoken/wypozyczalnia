<?php
include 'baza.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $haslo = $_POST['haslo'];

    $stmt = $conn->prepare("SELECT id, imie, nazwisko, haslo FROM adminLog WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $imie, $nazwisko, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {  // Перевіряємо, чи знайдено запис
        if (password_verify($haslo, $hashed_password)) {
            $_SESSION['adminLog_id'] = $id;
            $_SESSION['adminLog_name'] = $imie . " " . $nazwisko;
            header("Location: startBiblioteka.php");
            exit();
        } else {
            echo "Błędne hasło.";
        }
    } else {
        echo "Błędny email.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Logowanie dla administratorów biblioteki</title>
</head>

<body>
    <h2>Logowanie (Tylko dla administratorów)</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Hasło:</label>
        <input type="password" name="haslo" required><br>
        <button type="submit">Zaloguj się</button>
    </form>

    <a href="../startBiblioteka.php">Home</a>
</body>

</html>