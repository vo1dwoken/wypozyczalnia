<?php
include '../Main/baza.php';  // Підключаємо базу даних
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримуємо значення з форми
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $haslo = $_POST['haslo'];
    $imie = htmlspecialchars($_POST['imie']);
    $nazwisko = htmlspecialchars($_POST['nazwisko']);

    // Перевірка, чи вже існує користувач з таким email
    $stmt = $conn->prepare("SELECT id FROM czytelnik WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email już istnieje!";
    } else {
        // Хешуємо пароль
        $hashed_password = password_hash($haslo, PASSWORD_DEFAULT);

        // Додаємо користувача в базу даних
        $stmt = $conn->prepare("INSERT INTO czytelnik (email, haslo, imie, nazwisko) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $hashed_password, $imie, $nazwisko);

        if ($stmt->execute()) {
            echo "Rejestracja zakończona sukcesem! <a href='loginUser.php'>Zaloguj się</a>";
        } else {
            echo "Błąd: " . $conn->error;
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