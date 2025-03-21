CREATE DATABASE wypozyczalnia;
USE wypozyczalnia;

CREATE TABLE autor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwisko VARCHAR(100) NOT NULL,
    imie VARCHAR(100) NOT NULL
);

CREATE TABLE wydawca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL
);


CREATE TABLE tematyka (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL
);


CREATE TABLE ksiazka (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tytul VARCHAR(200) NOT NULL,
    autor_id INT,
    wydawca_id INT,
    rok_wydania YEAR,
    tematyka_id INT,
    FOREIGN KEY (autor_id) REFERENCES autor(id),
    FOREIGN KEY (wydawca_id) REFERENCES wydawca(id),
    FOREIGN KEY (tematyka_id) REFERENCES tematyka(id)
);

CREATE TABLE ulica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL
);


CREATE TABLE czytelnik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazwisko VARCHAR(100) NOT NULL,
    ulica_id INT,
    ulica_numer VARCHAR(10),
    mieszkanie_numer VARCHAR(10),
    FOREIGN KEY (ulica_id) REFERENCES ulica(id)
);


CREATE TABLE wypozyczenie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    czytelnik_id INT,
    ksiazka_id INT,
    FOREIGN KEY (czytelnik_id) REFERENCES czytelnik(id),
    FOREIGN KEY (ksiazka_id) REFERENCES ksiazka(id)
);

CREATE TABLE adminLog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imie VARCHAR(100) NOT NULL,
    nazwisko VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    haslo VARCHAR(255) NOT NULL
);

INSERT INTO autor (nazwisko, imie) VALUES 
('Sienkiewicz', 'Henryk'),
('Mickiewicz', 'Adam');

INSERT INTO wydawca (nazwa) VALUES 
('PWN'),
('Znak');

INSERT INTO tematyka (nazwa) VALUES 
('Historyczna'),
('Poezja');

INSERT INTO ksiazka (tytul, autor_id, wydawca_id, rok_wydania, tematyka_id) VALUES 
('Quo Vadis', 1, 1, 1896, 1),
('Pan Tadeusz', 2, 2, 1834, 2);

INSERT INTO ulica (nazwa) VALUES 
('Kwiatowa'),
('Ogrodowa');

INSERT INTO czytelnik (nazwisko, ulica_id, ulica_numer, mieszkanie_numer) VALUES 
('Nowak', 1, '10', '5'),
('Kowalski', 2, '20', '12');

INSERT INTO wypozyczenie (czytelnik_id, ksiazka_id) VALUES 
(1, 1),
(2, 2);

INSERT INTO adminLog (imie, nazwisko, email, haslo) 
VALUES ('Admin', 'Admin', 'admin@example.com', 
        '$2y$10$VdZKP2Jv5RnPj2pPqJ8y1OyqWJYZ8UuKDBFqkX9m2U7tAloYyBhG2');

CREATE VIEW ksiazka_widok AS
SELECT k.id, k.tytul, a.nazwisko AS autor, w.nazwa AS wydawca, t.nazwa AS tematyka, k.rok_wydania
FROM ksiazka k
JOIN autor a ON k.autor_id = a.id
JOIN wydawca w ON k.wydawca_id = w.id
JOIN tematyka t ON k.tematyka_id = t.id;

CREATE VIEW czytelnik_widok AS
SELECT c.id, c.nazwisko, u.nazwa AS ulica, c.ulica_numer, c.mieszkanie_numer
FROM czytelnik c
JOIN ulica u ON c.ulica_id = u.id;

CREATE VIEW wypozyczenie_widok AS
SELECT w.id, c.nazwisko AS czytelnik, k.tytul AS ksiazka
FROM wypozyczenie w
JOIN czytelnik c ON w.czytelnik_id = c.id
JOIN ksiazka k ON w.ksiazka_id = k.id;