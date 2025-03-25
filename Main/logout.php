<?php
session_start();
session_destroy();
header("Location: ../User/loginUser.php");
exit();
