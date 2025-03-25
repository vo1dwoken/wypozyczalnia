<?php
include '../Main/baza.php';  // Підключаємо базу даних
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримуємо значення з форми
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $haslo = $_POST['haslo'];
    $imie = htmlspecialchars($_POST['imie']);
    $nazwisko = htmlspecialchars($_POST['nazwisko']);
    $ulica_nazwa = htmlspecialchars($_POST['ulica_nazwa']);
    $ulica_numer = htmlspecialchars($_POST['ulica_numer']);
    $mieszkanie_numer = htmlspecialchars($_POST['mieszkanie_numer']);

    // Перевірка, чи вже існує користувач з таким email
    $stmt = $conn->prepare("SELECT id FROM czytelnik WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Email już istnieje!";
    } else {
        // Перевіряємо, чи існує вулиця, якщо ні – додаємо
        $stmt = $conn->prepare("SELECT id FROM ulica WHERE nazwa = ?");
        $stmt->bind_param("s", $ulica_nazwa);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($ulica_id);
            $stmt->fetch();
        } else {
            // Додаємо нову вулицю в таблицю
            $stmt = $conn->prepare("INSERT INTO ulica (nazwa) VALUES (?)");
            $stmt->bind_param("s", $ulica_nazwa);
            $stmt->execute();
            $ulica_id = $stmt->insert_id; // Отримуємо ID нової вулиці
        }

        // Хешуємо пароль
        $hashed_password = password_hash($haslo, PASSWORD_DEFAULT);

        // Додаємо користувача в базу даних
        $stmt = $conn->prepare("INSERT INTO czytelnik (imie, nazwisko, ulica_id, ulica_numer, mieszkanie_numer, email, haslo) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisss", $imie, $nazwisko, $ulica_id, $ulica_numer, $mieszkanie_numer, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: loginUser.php?registered=1"); // Перенаправляємо на логін
            exit();
        } else {
            $error_message = "Błąd rejestracji: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Rejestracja użytkownika</title>
    <link rel="stylesheet" href="../Main/style.css" />
</head>

<body>
    <h2 id="h2Reje">Rejestracja użytkownika</h2>
    <form method="POST">
        <label>Imię:</label>
        <input type="text" name="imie" required><br>
        <label>Nazwisko:</label>
        <input type="text" name="nazwisko" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Hasło:</label>
        <input type="password" name="haslo" required><br>
        <button type="submit">Zarejestruj się</button>
    </form>
</body>

</html>