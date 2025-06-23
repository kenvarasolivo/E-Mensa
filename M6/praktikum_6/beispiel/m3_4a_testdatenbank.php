<?php
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */
// Verbindung zur Datenbank herstellen
$link = mysqli_connect(
    "localhost",          // Host der Datenbank
    "root",               // Benutzername zur Anmeldung
    "root",  // Passwort
    "emensawerbeseite"    // Datenbankname
);

// Überprüfen, ob die Verbindung erfolgreich war
if (!$link) {
    echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
    exit();
}

// SQL-Abfrage definieren
$sql = "SELECT id, name, beschreibung
FROM gericht
ORDER BY name ASC
LIMIT 5;";

// Abfrage ausführen
$result = mysqli_query($link, $sql);
if (!$result) {
    echo "Fehler während der Abfrage: ", mysqli_error($link);
    exit();
}

// HTML-Dokument beginnen
echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Datenbankabfrage</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Ergebnisse der Datenbankabfrage</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Beschreibung</th>
            </tr>
        </thead>
        <tbody>';

// Ergebnisse in die Tabelle einfügen
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['id']. '</td>';
    echo '<td>' . $row['name']. '</td>';
    echo '<td>' . $row['beschreibung']. '</td>';
    echo '</tr>';
}

// HTML-Dokument abschließen
echo '        </tbody>
    </table>
</body>
</html>';

// Speicher freigeben und Verbindung schließen
mysqli_free_result($result);
mysqli_close($link);
?>
