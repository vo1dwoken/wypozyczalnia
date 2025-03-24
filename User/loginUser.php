<?php include 'buttonLogOutInReg.php'; ?>
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
    <form class="container">
        <h1 class="login-title">Login</h1>
        
        <section class="input-box">
            <input type="text" name="username" placeholder="Username">
            <i class="bx bxs-lock-alt"></i>
        </section>
        <section class="input-box">
            <input type="password" name="password" placeholder="Password">
            <i class="bx bxs-lock-alt"></i>
        </section>
        <button class="login-button" type="submit">Login</button>
    </form>
</body>

</html>