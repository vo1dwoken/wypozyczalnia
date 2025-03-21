<?php
$correct_password = 'admin123';
$hash_from_db = '$2y$10$VdZKP2Jv5RnPj2pPqJ8y1OyqWJYZ8UuKDBFqkX9m2U7tAloYyBhG2'; // Скопіюй хеш із бази!

if (password_verify($correct_password, $hash_from_db)) {
    echo "Hasło poprawne!";
} else {
    echo "Hasło błędne!";
}
?>