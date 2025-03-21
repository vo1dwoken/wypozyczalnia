CREATE DATABASE wypozyczalnia;
USE wypozyczalnia;

CREATE TABLE autor_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwisko VARCHAR(100) NOT NULL,
    imie VARCHAR(100) NOT NULL
);

CREATE TABLE wydawca_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL
);


CREATE TABLE tematyka_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL
);


CREATE TABLE ksiazka_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tytul VARCHAR(200) NOT NULL,
    autor_id INT,
    wydawca_id INT,
    rok_wydania YEAR,
    tematyka_id INT,
    FOREIGN KEY (autor_id) REFERENCES autor_MM(id),
    FOREIGN KEY (wydawca_id) REFERENCES wydawca_MM(id),
    FOREIGN KEY (tematyka_id) REFERENCES tematyka_MM(id)
);

CREATE TABLE ulica_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL
);


CREATE TABLE czytelnik_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwisko VARCHAR(100) NOT NULL,
    ulica_id INT,
    ulica_numer VARCHAR(10),
    mieszkanie_numer VARCHAR(10),
    FOREIGN KEY (ulica_id) REFERENCES ulica_MM(id)
);


CREATE TABLE wypozyczenie_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    czytelnik_id INT,
    ksiazka_id INT,
    FOREIGN KEY (czytelnik_id) REFERENCES czytelnik_MM(id),
    FOREIGN KEY (ksiazka_id) REFERENCES ksiazka_MM(id)
);

CREATE TABLE admin_MM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imie VARCHAR(100) NOT NULL,
    nazwisko VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    haslo VARCHAR(255) NOT NULL
);

INSERT INTO autor_MM (nazwisko, imie) VALUES 
('Sienkiewicz', 'Henryk'),
('Mickiewicz', 'Adam');

INSERT INTO wydawca_MM (nazwa) VALUES 
('PWN'),
('Znak');

INSERT INTO tematyka_MM (nazwa) VALUES 
('Historyczna'),
('Poezja');

INSERT INTO ksiazka_MM (tytul, autor_id, wydawca_id, rok_wydania, tematyka_id) VALUES 
('Quo Vadis', 1, 1, 1896, 1),
('Pan Tadeusz', 2, 2, 1834, 2);

INSERT INTO ulica_MM (nazwa) VALUES 
('Kwiatowa'),
('Ogrodowa');

INSERT INTO czytelnik_MM (nazwisko, ulica_id, ulica_numer, mieszkanie_numer) VALUES 
('Nowak', 1, '10', '5'),
('Kowalski', 2, '20', '12');

INSERT INTO wypozyczenie_MM (czytelnik_id, ksiazka_id) VALUES 
(1, 1),
(2, 2);

INSERT INTO admin_MM (imie, nazwisko, email, haslo) 
VALUES ('Admin', 'Admin', 'admin@example.com', 
        '$2y$10$VdZKP2Jv5RnPj2pPqJ8y1OyqWJYZ8UuKDBFqkX9m2U7tAloYyBhG2');

CREATE VIEW ksiazka_widok_MM AS
SELECT k.id, k.tytul, a.nazwisko AS autor, w.nazwa AS wydawca, t.nazwa AS tematyka, k.rok_wydania
FROM ksiazka_MM k
JOIN autor_MM a ON k.autor_id = a.id
JOIN wydawca_MM w ON k.wydawca_id = w.id
JOIN tematyka_MM t ON k.tematyka_id = t.id;

CREATE VIEW czytelnik_widok_MM AS
SELECT c.id, c.nazwisko, u.nazwa AS ulica, c.ulica_numer, c.mieszkanie_numer
FROM czytelnik_MM c
JOIN ulica_MM u ON c.ulica_id = u.id;

CREATE VIEW wypozyczenie_widok_MM AS
SELECT w.id, c.nazwisko AS czytelnik, k.tytul AS ksiazka
FROM wypozyczenie_MM w
JOIN czytelnik_MM c ON w.czytelnik_id = c.id
JOIN ksiazka_MM k ON w.ksiazka_id = k.id;
