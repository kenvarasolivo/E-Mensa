<link rel="stylesheet" href="/css/index.css">

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewertungen</title>
</head>
<body>
<table>
    <tr>
        <th>Gericht</th>
        <th>Sternebewertung</th>
        <th>Bemerkung</th>
        <th>Datum</th>
        <th>Aktion</th>
    </tr>
    @foreach($ratings as $rating)
        <tr>
            <td>{{ htmlspecialchars($rating['gericht_name']) }}</td>
            <td>{{ htmlspecialchars($rating['sterne']) }}</td>
            <td>{{ htmlspecialchars($rating['bemerkung']) }}</td>
            <td>{{ htmlspecialchars($rating['bewertungszeitpunkt']) }}</td>
            <td>
                <a href="/loeschen?id={{ $rating['id'] }}"
                   onclick="return confirm('Sind Sie sicher, dass Sie diese Bewertung löschen möchten?');">
                    Löschen
                </a>
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
