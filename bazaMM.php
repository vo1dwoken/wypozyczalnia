<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "wypozyczalnia";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Błąd połączenia: " . htmlspecialchars($conn->connect_error));
}

$conn->set_charset("utf8");
?>
