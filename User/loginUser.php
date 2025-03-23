<?php
include '../Main/baza.php';  // Правильний шлях до bazy.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $haslo = $_POST['haslo'];

    // Перевірка в базі даних на існування користувача
    $stmt = $conn->prepare("SELECT id, nazwisko, haslo FROM czytelnik WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nazwisko, $hashed_password);
    $stmt->fetch();

    // Перевірка пароля
    if (password_verify($haslo, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $nazwisko;
        header("Location: userPanel.php");  // Перенаправлення на панель користувача
        exit();
    } else {
        echo "Błędny email lub hasło.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Logowanie użytkownika</title>
</head>

<body>
    <h2>Logowanie dla użytkowników</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Hasło:</label>
        <input type="password" name="haslo" required><br>
        <button type="submit">Zaloguj się</button>
    </form>
</body>

</html>