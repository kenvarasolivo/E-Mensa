-- Neue Tabelle "Bewertung" erstellen
CREATE TABLE bewertung (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           benutzer_id INT NOT NULL,
                           gericht_id BIGINT NOT NULL,
                           bemerkung TEXT NOT NULL,
                           sterne ENUM('sehr gut', 'gut', 'schlecht', 'sehr schlecht') NOT NULL,
                           bewertungszeitpunkt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           FOREIGN KEY (benutzer_id) REFERENCES benutzer(id) ON DELETE CASCADE,
                           FOREIGN KEY (gericht_id) REFERENCES gericht(id) ON DELETE CASCADE
);