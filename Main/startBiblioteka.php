<?php include 'baza.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Open+Sans:wght@400;600&family=Poppins:wght@400;600&display=swap"
      rel="stylesheet">
    <title>Biblioteka</title>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../logo.png" alt="Logo" class="logo">
            <h1>Biblioteka</h1>
            <?php include 'buttonLogOutInReg.php'; ?>
        </div>
        <?php include 'navigation.php'; ?>
    </header>
    <main>
    <h2>Witamy w Panele Admin Biblioteki Online!</h2>

    <section class="info-section">
        <div class="info-box">
             <img src="https://cdn-icons-png.flaticon.com/512/864/864685.png" alt="Book Icon">
            <h3>Kolekcja książek</h3>
            <p>Nasza biblioteka oferuje ponad 10 000 książek z różnych dziedzin — od literatury pięknej po naukę i technologię.</p>
        </div>

        <div class="info-box">
            <img src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png" alt="Users Icon">
            <h3>Zarządzanie użytkownikami</h3>
            <p>Dodawaj, edytuj lub usuwaj konta użytkowników. Sprawdzaj ich historię wypożyczeń i aktualne rezerwacje.</p>
        </div>

        <div class="info-box">
            <img src="https://cdn-icons-png.flaticon.com/512/1250/1250615.png" alt="Pencil Icon">
            <h3>Zarządzanie książkami</h3>
            <p>Dodawaj nowe ksiązki i autorów. Bądź na bieżąco z aktualnymi ofertami dla wypożyczenia!</p>
        </div>
    </section>
</main>

    <footer>
        <p>© 2025. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
