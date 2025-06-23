<!--pages/werbeseite.blade.php-->

@extends('examples.layout.werbeseite_layout')

@section('text')
        <img id="mensabild" src="img/mensa.jpg">
        <h1 id="ankun">Bald gibt es Essen auch online;)</h1>

        <div class="paragraph">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum egestas libero at tempus. Aliquam cursus neque nec nibh porttitor laoreet. Donec vehicula nibh tempus facilisis posuere. Ut pretium lectus ac nulla molestie tincidunt. Vestibulum et aliquet leo. Ut diam sem, tempor vel tortor vel, faucibus tristique sem. Nullam turpis tellus, imperdiet a lacus ullamcorper, pellentesque egestas arcu.</p>
        </div>

        <div class="par">
            <h1 id="menu">Köstlichkeiten, die sie erwarten</h1>
        </div>
@endsection

@section('main')

    <h2>Meinungen unserer Gäste</h2>
    @if (!empty($highlightedRatings))
        <div class="guest-reviews">
            @foreach ($highlightedRatings as $rating)
                <div class="guest-review">
                    <h3>{{ htmlspecialchars($rating['gericht_name'] ?? 'Unbekannt') }}</h3>
                    <p>"{{ htmlspecialchars($rating['bemerkung'] ?? 'Keine Bemerkung') }}"</p>
                    <p><strong>Bewertung:</strong> {{ htmlspecialchars($rating['sterne'] ?? 'Unbekannt') }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p>Derzeit sind keine Meinungen verfügbar.</p>
    @endif
    <div class="navigation-links">
        <h2>Navigieren Sie zu:</h2>
        <ul>
            <li><a href="/bewertungen">Letzte Bewertungen ansehen</a></li>
            <li><a href="/meinebewertungen">Meine Bewertungen</a></li>
        </ul>
    </div>
    <div class="sort-buttons">
        <a href="?sort=asc" class="button">Sortiere aufsteigend</a>
        <a href="?sort=desc" class="button">Sortiere absteigend</a>
    </div>
    <br>

    <table>
        <thead>
            <tr>
                <th>Bild</th>
                <th>Gerichte</th>
                <th>Preis intern</th>
                <th>Preis extern</th>
                <th>Allergene</th>
                <th>Bewerten</th>
            </tr>
        </thead>
        @foreach($gerichte as $gericht)
            <tbody>
                <tr>
                    <td>
                        <!-- Display the dish image or fallback to "00_image_missing.jpg" -->
                        <img
                                src="img/gerichte/{{ $gericht['bildname'] ?? '00_image_missing.jpg' }}"
                                onerror="this.onerror=null; this.src='img/00_image_missing.jpg';"
                                alt="Bild von {{ htmlspecialchars($gericht['name']) }}"
                                width="150"
                                height="150">
                    </td>
                    <td>{{ htmlspecialchars($gericht['name']) }}</td>
                    <td>{{ number_format($gericht['preisintern'], 2, ',', '.') }}</td>
                    <td>{{ number_format($gericht['preisextern'], 2, ',', '.') }}</td>
                    <td>{{ $gericht['formatted_allergens'] }}</td>
                    <td>
                        <!-- Link to the rating form -->
                        <a href="/bewertung?gericht_id={{ $gericht['id'] }}" class="button">Bewerten</a>
                    </td>
                </tr>
            </tbody>
        @endforeach
    </table>

    <h3>Allergen Details:</h3>
    <ul>
        @foreach($allergenDetails as $code => $name)
            <li>{{ $code }}: {{ $name }}</li>
        @endforeach
    </ul>

@endsection
