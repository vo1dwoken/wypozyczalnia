-- DROP DATABASE wypozyczalnia;
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
    ilosc_calkowita INT NOT NULL DEFAULT 0,
    ilosc_dostepnych INT NOT NULL DEFAULT 0,
    dostepnosc INT NOT NULL DEFAULT 0,
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
    imie VARCHAR(100) NOT NULL,
    nazwisko VARCHAR(100) NOT NULL,
    ulica_id INT,
    ulica_numer VARCHAR(10),
    mieszkanie_numer VARCHAR(10),
    email VARCHAR(255) NOT NULL UNIQUE,
    haslo VARCHAR(255) NOT NULL,
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

INSERT INTO autor (nazwisko, imie) VALUES
('Sienkiewicz', 'Henryk'),
('Kapuściński', 'Ryszard'),
('Mickiewicz', 'Adam'),
('Prus', 'Bolesław'),
('Orwell', 'George'),
('Dickens', 'Charles'),
('Austen', 'Jane'),
('Hemingway', 'Ernest'),
('Rowling', 'J.K.'),
('Tolkien', 'J.R.R.');

INSERT INTO wydawca (nazwa) VALUES
('Nasza Księgarnia'),
('Znak'),
('Wydawnictwo Ossolineum'),
('Wydawnictwo W.A.B.'),
('Czytelnik'),
('Iskry'),
('Wydawnictwo MG'),
('Wydawnictwo PIW'),
('Media Rodzina'),
('Prószyński i S-ka');

INSERT INTO tematyka (nazwa) VALUES
('Historia'),
('Reportaż'),
('Poemat'),
('Powieść'),
('Fantasy'),
('Science Fiction'),
('Dramat'),
('Przygodowa');

INSERT INTO ksiazka (tytul, autor_id, wydawca_id, rok_wydania, tematyka_id, ilosc_calkowita, ilosc_dostepnych, dostepnosc) VALUES
('Quo Vadis', 1, 1, 1896, 1, 100, 90, 1),  
('Imperium', 2, 2, 1993, 2, 50, 40, 1),    
('Pan Tadeusz', 3, 3, 1834, 3, 200, 180, 1),
('Lalka', 4, 4, 1890, 4, 150, 130, 1),      
('1984', 5, 5, 1949, 5, 120, 100, 1),        
('Opowieść wigilijna', 6, 6, 1843, 7, 80, 70, 1), 
('Duma i uprzedzenie', 7, 7, 1813, 3, 200, 190, 1), 
('Stary człowiek i morze', 8, 8, 1952, 7, 50, 45, 1), 
('Harry Potter i Kamień Filozoficzny', 9, 9, 1997, 6, 300, 280, 1), 
('Hobbit', 10, 10, 1937, 5, 150, 140, 1);     

INSERT INTO ulica (nazwa) VALUES
('Krakowska'),
('Warszawska'),
('Gdańska'),
('Łódzka'),
('Wrocławska'),
('Poznańska'),
('Krakowska'),
('Zielona'),
('Świętokrzyska'),
('Leśna');

INSERT INTO czytelnik (imie, nazwisko, ulica_id, ulica_numer, mieszkanie_numer, email, haslo) VALUES
('Jan', 'Kowalski', 1, '12', '3', 'jan.kowalski@example.com', 'haslo123'),
('Anna', 'Nowak', 2, '45', '7', 'anna.nowak@example.com', 'haslo456'),
('Marek', 'Wiśniewski', 3, '89', '5', 'marek.wisniewski@example.com', 'haslo789'),
('Maria', 'Lewandowska', 4, '123', '2', 'maria.lewandowska@example.com', 'haslo101'),
('Piotr', 'Zieliński', 5, '58', '1', 'piotr.zielinski@example.com', 'haslo202'),
('Katarzyna', 'Wójcik', 6, '98', '8', 'katarzyna.wojcik@example.com', 'haslo303'),
('Tomasz', 'Kaczmarek', 7, '30', '4', 'tomasz.kaczmarek@example.com', 'haslo404'),
('Zofia', 'Jankowska', 8, '76', '9', 'zofia.jankowska@example.com', 'haslo505'),
('Łukasz', 'Nowicki', 9, '120', '11', 'lukasz.nowicki@example.com', 'haslo606'),
('Julia', 'Pawlak', 10, '32', '5', 'julia.pawlak@example.com', 'haslo707');

INSERT INTO wypozyczenie (czytelnik_id, ksiazka_id) VALUES
(1, 1),  
(2, 2),  
(3, 3),  
(4, 4),  
(5, 5), 
(6, 6),  
(7, 7),  
(8, 8),  
(9, 9),  
(10, 10), 
(1, 5),  
(2, 6),  
(3, 7),  
(4, 8), 
(5, 9);  
