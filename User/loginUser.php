<?php
include '../Main/baza.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $haslo = $_POST['haslo'];

    // Перевірка наявності користувача або адміністратора в базі
    // Пошук користувача
    $stmt_user = $conn->prepare("SELECT id, imie, nazwisko, haslo FROM czytelnik WHERE email = ?");
    $stmt_user->bind_param("s", $email);
    $stmt_user->execute();
    $stmt_user->store_result();
    $stmt_user->bind_result($id, $imie, $nazwisko, $hashed_password);
    $stmt_user->fetch();

    // Пошук адміністратора
    $stmt_admin = $conn->prepare("SELECT id, imie, nazwisko, haslo FROM adminLog WHERE email = ?");
    $stmt_admin->bind_param("s", $email);
    $stmt_admin->execute();
    $stmt_admin->store_result();
    $stmt_admin->bind_result($admin_id, $admin_imie, $admin_nazwisko, $admin_hashed_password);
    $stmt_admin->fetch();

    if ($stmt_user->num_rows > 0) {
        // Якщо це користувач
        if (password_verify($haslo, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $imie . " " . $nazwisko;
            header("Location: wypozyczenia.php");  // Перенаправлення на панель користувача
            exit();
        } else {
            echo "Błędne hasło dla użytkownika.";
        }
    } elseif ($stmt_admin->num_rows > 0) {
        // Якщо це адміністратор
        if (password_verify($haslo, $admin_hashed_password)) {
            $_SESSION['adminLog_id'] = $admin_id;
            $_SESSION['adminLog_name'] = $admin_imie . " " . $admin_nazwisko;
            header("Location: ../Main/startBiblioteka.php");  // Перенаправлення на панель адміністратора
            exit();
        } else {
            echo "Błędne hasło dla administratora.";
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
</head>

<body>
    <img src="../images/logowanie.jpg" id="logowanieImage">
    <form class="container" method="POST">
        <h1 class="login-title">Login</h1>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <section class="input-box">
            <input type="email" name="email" placeholder="Email" required>
            <i class="bx bxs-lock-alt"></i>
        </section>
        <section class="input-box">
            <input type="password" name="haslo" placeholder="Password" required>
            <i class="bx bxs-lock-alt"></i>
        </section>
        <button class="login-button" type="submit">Login</button>
        <div class="small-buttons">
            <a href="../startBiblioteka.php" class="small-btn">Home</a>
            <a href="registerUser.php" class="small-btn">Register</a>
        </div>
    </form>
</body>

</html>