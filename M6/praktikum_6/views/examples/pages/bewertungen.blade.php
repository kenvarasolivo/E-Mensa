<link rel="stylesheet" href="/css/index.css">
<!DOCTYPE html>
<html lang="de">
<table>
    <tr>
        <th>Gericht</th>
        <th>Sternebewertung</th>
        <th>Bemerkung</th>
        <th>Datum</th>
        <th>Aktion</th>
    </tr>
    @foreach($ratings as $rating)
        <tr class="{{ $rating['highlighted'] == 1 ? 'highlighted' : '' }}">
            <td>{{ htmlspecialchars($rating['gericht_name'] ?? 'Unbekannt') }}</td>
            <td>{{ htmlspecialchars($rating['sterne'] ?? 'Unbekannt') }}</td>
            <td>{{ htmlspecialchars($rating['bemerkung'] ?? 'Unbekannt') }}</td>
            <td>{{ htmlspecialchars($rating['bewertungszeitpunkt'] ?? 'Unbekannt') }}</td>
            <td>
                @if (!empty($_SESSION['admin']) && $_SESSION['admin'] == 1 && isset($rating['highlighted']))
                    @if ($rating['highlighted'] == 1)
                        <a href="/abwaehlen?id={{ $rating['id'] }}">Hervorhebung abwählen</a>
                    @else
                        <a href="/hervorheben?id={{ $rating['id'] }}">Hervorheben</a>
                    @endif
                @else
                    Keine Aktion verfügbar
                @endif
            </td>

        </tr>
    @endforeach
</table>
