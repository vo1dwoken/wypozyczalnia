<?php
session_start();
session_destroy();
header("Location: loginUser.php");  // Переадресація на сторінку логіну користувача
exit();
