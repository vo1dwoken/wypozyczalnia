<?php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "wypozyczalnia";

// Дані для адміністратора
$admin_email = "admin3@example.com";     // Емейл адміністратора
$admin_password = "adminPassword";      // Пароль для хешування
$admin_first_name = "Admin";            // Ім'я адміністратора
$admin_last_name = "Adminowicz";        // Прізвище адміністратора

// Генерація хешу паролю
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Підключення до бази даних
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Перевірка з'єднання
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL запит для вставки нового адміністратора в таблицю adminLog
$sql = "INSERT INTO adminLog (email, haslo, imie, nazwisko) 
        VALUES ('$admin_email', '$hashed_password', '$admin_first_name', '$admin_last_name')";

// Виконання запиту
if ($conn->query($sql) === TRUE) {
    echo "Адміністратор успішно доданий у базу даних!";
} else {
    echo "Помилка: " . $sql . "<br>" . $conn->error;
}

// Закриття з'єднання
$conn->close();
