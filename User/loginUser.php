<?php
include '../Main/baza.php';  // Підключення до бази даних
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $haslo = $_POST['haslo'];  // Перевіряємо значення пароля

    // Перевірка наявності користувача в базі
    $stmt = $conn->prepare("SELECT id, imie, nazwisko, haslo FROM czytelnik WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $imie, $nazwisko, $hashed_password);
    $stmt->fetch();

    // Перевірка пароля
    if ($stmt->num_rows > 0) {  // Перевіряємо, чи знайдено запис
        if (password_verify($haslo, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $imie . " " . $nazwisko;
            // Перенаправлення на сторінку wypozyczenia.php після успішного входу
            header("Location: wypozyczenia.php");
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
    <title>Logowanie użytkownika</title>
    <link rel="stylesheet" href="../Main/style.css" />
    <?php include 'navigation.php'; ?>
</head>

<body>
    <img src="../images/logowanie.jpg" id="logowanieImage">
    <form class="container" method="POST">
        <h1 class="login-title">Login</h1>

        <section class="input-box">
            <input type="email" name="email" placeholder="Email" required>
            <i class="bx bxs-lock-alt"></i>
        </section>
        <section class="input-box">
            <input type="password" name="haslo" placeholder="Password" required>
            <i class="bx bxs-lock-alt"></i>
        </section>
        <button class="login-button" type="submit">Login</button>
    </form>
</body>

</html>