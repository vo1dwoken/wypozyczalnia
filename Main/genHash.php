<?php
// Задай пароль для нового адміністратора
$plain_password = 'panostap123';  // Заміни на бажаний пароль

// Генерація хешу пароля
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Виведення хешу
echo $hashed_password;
