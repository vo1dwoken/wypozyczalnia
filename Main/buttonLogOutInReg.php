<?php
//session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php'); 
    exit();
}
?>


<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>
    <form method="POST">
        <button type="submit" name="logout" class="action-button" id="Logout">Log out</button>
    </form>
<?php else: ?>
    <button onclick="window.location.href='login.php';" class="action-button" id="Login">Log in</button>
    <button onclick="window.location.href='register.php';" class="action-button" id="Regin">Reg in</button>
<?php endif; ?>
