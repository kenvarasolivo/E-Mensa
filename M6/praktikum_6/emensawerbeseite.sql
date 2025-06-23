/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */
CREATE DATABASE IF NOT EXISTS emensawerbeseite
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE emensawerbeseite;

CREATE TABLE gericht (
                         id INT8 PRIMARY KEY,
                         name VARCHAR(80) NOT NULL,
                         beschreibung VARCHAR(800) NOT NULL,
                         erfasst_am DATE NOT NULL,
                         vegetarisch BOOLEAN NOT NULL DEFAULT FALSE,
                         vegan BOOLEAN NOT NULL DEFAULT FALSE,
                         preisintern DOUBLE NOT NULL CHECK (preisintern > 0),
                         preisextern DOUBLE NOT NULL CHECK (preisintern <= preisextern)
);

CREATE TABLE allergen (
                          code CHAR(4) PRIMARY KEY,
                          name VARCHAR(300) NOT NULL,
                          typ VARCHAR(20) NOT NULL DEFAULT 'allergen'
);

CREATE TABLE kategorie (
                           id INT8 PRIMARY KEY,
                           name VARCHAR(80) NOT NULL,
                           eltern_id INT8,
                           bildname VARCHAR(200)
);

CREATE TABLE gericht_hat_allergen (
                                      code CHAR(4) NOT NULL,
                                      gericht_id INT8 NOT NULL
);

CREATE TABLE gericht_hat_kategorie (
                                       gericht_id INT8 NOT NULL,
                                       kategorie_id INT8 NOT NULL
);


CREATE TABLE IF NOT EXISTS newsletter_anmeldungen (
                                                      id INT AUTO_INCREMENT PRIMARY KEY,
                                                      name VARCHAR(255) NOT NULL,
                                                      email VARCHAR(255) NOT NULL,
                                                      language VARCHAR(50),
                                                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS visitor_counter (
                                               id INT PRIMARY KEY,
                                               count INT NOT NULL DEFAULT 0
);


INSERT INTO visitor_counter (id, count) VALUES (1, 0)
ON DUPLICATE KEY UPDATE count = count;

-- Tabelle Ersteller
CREATE TABLE Ersteller (
                           ID INT AUTO_INCREMENT PRIMARY KEY,
                           Name VARCHAR(255) NOT NULL DEFAULT 'anonym',
                           Email VARCHAR(255) NOT NULL
);

-- Tabelle Wunschgericht
CREATE TABLE Wunschgericht (
                               ID INT AUTO_INCREMENT PRIMARY KEY,
                               Name VARCHAR(255) NOT NULL,
                               Beschreibung TEXT,
                               Erstellungsdatum DATE NOT NULL,
                               ErstellerID INT NOT NULL,
                               FOREIGN KEY (ErstellerID) REFERENCES Ersteller(ID)
);