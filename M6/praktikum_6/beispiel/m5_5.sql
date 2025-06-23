DELIMITER $$

CREATE PROCEDURE IncrementAnmeldung(IN userId INT)
BEGIN
    -- Increment the anzahlanmeldungen for the given user ID
UPDATE benutzer
SET anzahlanmeldungen = anzahlanmeldungen + 1
WHERE id = userId;

END $$

DELIMITER ;