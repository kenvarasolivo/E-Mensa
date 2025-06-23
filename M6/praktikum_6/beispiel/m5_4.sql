CREATE VIEW view_suppengerichte AS
SELECT *
FROM gericht
WHERE LOWER(name) LIKE '%suppe%';

CREATE VIEW view_anmeldungen AS
SELECT name, email, COUNT(*) AS anmeldeanzahl
FROM newsletter_anmeldungen
GROUP BY name, email
ORDER BY anmeldeanzahl DESC;

CREATE VIEW view_kategoriegerichte_vegetarisch AS
SELECT k.name AS kategorie_name, g.name AS gericht_name
FROM kategorie k
         LEFT JOIN gericht_hat_kategorie ghk ON k.id = ghk.kategorie_id
         LEFT JOIN gericht g ON ghk.gericht_id = g.id
WHERE g.vegetarisch= TRUE OR g.vegetarisch IS NULL;