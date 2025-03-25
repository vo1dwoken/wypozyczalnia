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
    <img src="../images/logowanie.jpg" id="logowanieImage">
    <form class="container" method="POST">
        <h1 class="login-title">Rejestracja</h1>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <section class="input-box">
            <input type="text" name="imie" placeholder="Imię" required>
            <i class="bx bxs-user"></i>
        </section>
        <section class="input-box">
            <input type="text" name="nazwisko" placeholder="Nazwisko" required>
            <i class="bx bxs-user"></i>
        </section>

        <section class="input-box">
            <input type="text" name="ulica_nazwa" placeholder="Nazwa ulicy" required>
        </section>

        <section class="input-box">
            <input type="text" name="ulica_numer" placeholder="Numer budynku" required>
        </section>

        <section class="input-box">
            <input type="text" name="mieszkanie_numer" placeholder="Numer mieszkania" required>
        </section>

        <section class="input-box">
            <input type="email" name="email" placeholder="Email" required>
            <i class="bx bxs-envelope"></i>
        </section>
        <section class="input-box">
            <input type="password" name="haslo" placeholder="Hasło" required>
            <i class="bx bxs-lock-alt"></i>
        </section>

        <button class="login-button" type="submit">Zarejestruj się</button>

        <!-- Кнопки "Home" і "Login" -->
        <div class="small-buttons">
            <a href="../startBiblioteka.php" class="small-btn">Home</a>
            <a href="loginUser.php" class="small-btn">Login</a>
        </div>
    </form>
</body>

</html>