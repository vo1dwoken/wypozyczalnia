<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: loginUser.php");  // Якщо не увійшов, перенаправляє на логін
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Panel użytkownika</title>
</head>

<body>
    <h1>Witaj, <?php echo $_SESSION['user_name']; ?>!</h1>
    <p>Twoje konto czytelnika.</p>
    <a href="logout.php">Wyloguj się</a> <!-- Посилання для виходу -->
</body>

</html>