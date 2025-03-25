<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: loginUser.php");  // Якщо користувач не увійшов, перенаправляємо на сторінку логіну
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Main/style.css">
    <title>Profil użytkownika</title>
</head>

<body>
    <header>
        <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
            <h1>Profil użytkownika</h1>
        </div>
        <?php include 'navigation.php'; ?>
    </header>
    <main>
        <h2>Witaj, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p>To jest Twój profil.</p>
        <!-- Додаткові дані профілю можна тут вивести -->
        <a href="/User/logout.php">Wyloguj się</a>
    </main>

    <footer>
        <p>© 2025. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>

</html>