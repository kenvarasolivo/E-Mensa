<?php
$link = mysqli_connect("localhost", "root", "root", "emensawerbeseite");
mysqli_set_charset($link, "utf8mb4");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ersteller_name = trim($_POST['ersteller_name']) ?: 'anonym'; // Standardname "anonym", wenn leer
    $ersteller_email = trim($_POST['ersteller_email']);
    $gericht_name = trim($_POST['gericht_name']);
    $beschreibung = trim($_POST['beschreibung']);
    $datum = date('Y-m-d'); // Aktuelles Datum

    $errors = [];

    // Validierungen
    if (!filter_var($ersteller_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ungültige E-Mail-Adresse.";
    }
    if (empty($gericht_name)) {
        $errors[] = "Bitte geben Sie einen Namen für das Gericht an.";
    }

    if (empty($errors)) {
        // Prüfen, ob der Ersteller existiert
        $stmt = $link->prepare("SELECT ID FROM Ersteller WHERE Email = ?");
        $stmt->bind_param("s", $ersteller_email);
        $stmt->execute();
        $stmt->bind_result($ersteller_id);
        $stmt->fetch();
        $stmt->close();

        if (!$ersteller_id) {
            // Falls der Ersteller nicht existiert, hinzufügen
            $stmt = $link->prepare("INSERT INTO Ersteller (Name, Email) VALUES (?, ?)");
            $stmt->bind_param("ss", $ersteller_name, $ersteller_email);
            $stmt->execute();
            $ersteller_id = $stmt->insert_id; // Neue ID speichern
            $stmt->close();
        }

        // Gericht hinzufügen
        $stmt = $link->prepare("INSERT INTO Wunschgericht (Name, Beschreibung, Erstellungsdatum, ErstellerID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $gericht_name, $beschreibung, $datum, $ersteller_id);
        if ($stmt->execute()) {
            echo "<p>Wunschgericht erfolgreich eingereicht!</p>";
        } else {
            echo "<p>Fehler beim Einreichen des Wunschgerichts.</p>";
        }
        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>

<form method="POST" action="">
    <label for="ersteller_name">Ihr Name:</label>
    <input type="text" id="ersteller_name" name="ersteller_name" placeholder="Name">
    <br>
    <label for="ersteller_email">Ihre E-Mail:</label>
    <input type="email" id="ersteller_email" name="ersteller_email" required>
    <br>
    <label for="gericht_name">Name des Wunschgerichts:</label>
    <input type="text" id="gericht_name" name="gericht_name" required>
    <br>
    <label for="beschreibung">Beschreibung:</label>
    <textarea id="beschreibung" name="beschreibung"></textarea>
    <br>
    <button type="submit">Wunsch abschicken</button>
</form>