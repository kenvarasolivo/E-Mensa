<?php
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */
const GET_PARAM_MIN_STARS = 'search_min_stars';
const GET_PARAM_SEARCH_TEXT = 'search_text';

/**
 * List of all allergens.
 */

$texts = [
    'de' => [
        'title' => 'Gericht',
        'meal_name' => 'Süßkartoffeltaschen mit Frischkäse und Kräutern gefüllt',
        'description' => 'Beschreibung',
        'ratings' => 'Bewertungen (Insgesamt: ',
        'filter' => 'Filter:',
        'search' => 'Suchen',
        'author' => 'Autor',
        'text' => 'Text',
        'stars' => 'Sterne',
        'allergens' => 'Allergene:',
        'way.' => 'Die Süßkartoffeln werden vorsichtig aufgeschnitten und der Frischkäse eingefüllt',
        'price_intern' => 'Preis intern',
        'price_extern' => 'Preis extern'

    ],
    'en' => [
        'title' => 'Dish',
        'meal_name' => 'Sweet Potato Pockets filled with Cream Cheese and Herbs',
        'description' => 'Description',
        'ratings' => 'Ratings (Total: ',
        'filter' => 'Filter:',
        'search' => 'Search',
        'author' => 'Author',
        'text' => 'Text',
        'stars' => 'Stars',
        'allergens' => 'Allergens:',
        'way.' => 'The sweet potatoes are carefully cut open and the cream cheese is added.',
        'price_intern' => 'Internal Price',
        'price_extern' => 'External Price'
    ]
];

// Determine language based on GET parameter, default to German if not set
if (isset($_GET['sprache']) && $_GET['sprache'] === 'en') {
    $lang = 'en';
} else {
    $lang = 'de';
}

if (isset($_GET["show_description"])) {
    if ($_GET["show_description"] == 1) {
        $show = 0;
    } else {
        $show = 1;
    }
} else {
    $show = 0;
}

$allergens = [
    11 => 'Gluten',
    12 => 'Krebstiere',
    13 => 'Eier',
    14 => 'Fisch',
    17 => 'Milch'
];

$meal = [
    'name' => 'Süßkartoffeltaschen mit Frischkäse und Kräutern gefüllt',
    'description' => 'Die Süßkartoffeln werden vorsichtig aufgeschnitten und der Frischkäse eingefüllt.',
    'price_intern' => 2.90,
    'price_extern' => 3.90,
    'allergens' => [11, 13],
    'amount' => 42 // Number of available meals
];

$ratings = [
    [
        'text' => 'Die Kartoffel ist einfach klasse. Nur die Fischstäbchen schmecken nach Käse. ',
        'author' => 'Ute U.',
        'stars' => 2
    ],
    [
        'text' => 'Sehr gut. Immer wieder gerne',
        'author' => 'Gustav G.',
        'stars' => 4
    ],
    [
        'text' => 'Der Klassiker für den Wochenstart. Frisch wie immer',
        'author' => 'Renate R.',
        'stars' => 4
    ],
    [
        'text' => 'Kartoffel ist gut. Das Grüne ist mir suspekt.',
        'author' => 'Marta M.',
        'stars' => 3
    ]
];



$showRatings = []; // Clear the array at the beginning

if (isset($_GET['rating_filter']) && $_GET['rating_filter'] === 'top') {
    // Apply the 'top' filter
    $maxStars = max(array_column($ratings, 'stars'));
    foreach ($ratings as $rating) {
        if ($rating['stars'] === $maxStars) {
            $showRatings[] = $rating;
        }
    }
} elseif (isset($_GET['rating_filter']) && $_GET['rating_filter'] === 'flop') {
    $minStars = min(array_column($ratings, 'stars'));
    foreach ($ratings as $rating) {
        if ($rating['stars'] === $minStars) {
            $showRatings[] = $rating;
        }
    }
} elseif (!empty($_GET[GET_PARAM_SEARCH_TEXT])) {
    $searchTerm = $_GET[GET_PARAM_SEARCH_TEXT];
    foreach ($ratings as $rating) {
        if (stripos($rating['text'], $searchTerm) !== false) {
            $showRatings[] = $rating;
        }
    }
} else {
    $showRatings = $ratings;
}

function calcMeanStars(array $ratings): float {
    $sum = 0;
    foreach ($ratings as $rating) {
        $sum += $rating['stars']; // Add each rating to the total sum
    }

    if (count($ratings) > 0) {
        return $sum / count($ratings); // Calculate the mean
    } else {
        return 0;
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $texts[$lang]['title']; ?>: <?php echo $meal['name']; ?></title>
    <style>
        * {
            font-family: Arial, serif;
        }
        .rating {
            color: darkgray;
        }
    </style>
</head>
<body>
<!-- Language selection links -->
<p>
    <a href="?sprache=de<?php echo isset($_GET[GET_PARAM_SEARCH_TEXT]) ? '&search_text=' . htmlspecialchars($_GET[GET_PARAM_SEARCH_TEXT]) : ''; ?>">
        Deutsch
    </a> |
    <a href="?sprache=en<?php echo isset($_GET[GET_PARAM_SEARCH_TEXT]) ? '&search_text=' . htmlspecialchars($_GET[GET_PARAM_SEARCH_TEXT]) : ''; ?>">
        English
    </a>
</p>
<h1><?php echo $texts[$lang]['title']; ?>: <?php echo $texts[$lang]['meal_name']; ?></h1>

<a href="<?php echo "?show_description=$show"?>">Show description?</a>
<p><?php if (!$show) {
        echo $texts[$lang]['way.'];
    }
    ?></p>
<p><?php echo $texts[$lang]['price_intern'] . ": " . number_format($meal['price_intern'], 2, ',', '') . "€"; ?></p>
<p><?php echo $texts[$lang]['price_extern'] . ": " . number_format($meal['price_extern'], 2, ',', '') . "€"; ?></p>
<h1><?php echo $texts[$lang]['ratings'] . calcMeanStars($ratings) . ')'; ?></h1>
<form method="get">
    <label for="rating_filter">Filter:</label>
    <input id="search_text" type="text" name="search_text"
           value="<?php if (isset($_GET[GET_PARAM_SEARCH_TEXT])) { echo htmlspecialchars($_GET[GET_PARAM_SEARCH_TEXT]); } ?>">
    <select name="rating_filter">
        <option value="">Alle</option>
        <option value="top" <?php if (isset($_GET['rating_filter']) && $_GET['rating_filter'] === 'top') { echo 'selected'; } ?>>Top-Bewertungen</option>
        <option value="flop" <?php if (isset($_GET['rating_filter']) && $_GET['rating_filter'] === 'flop') { echo 'selected'; } ?>>Flop-Bewertungen</option>
    </select>
    <input type="submit" value="Suchen">
</form>

<table class="rating">
    <thead>
    <tr>
        <td><?php echo $texts[$lang]['author']; ?></td>
        <td><?php echo $texts[$lang]['text']; ?></td>
        <td><?php echo $texts[$lang]['stars']; ?></td>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($showRatings as $rating) {
        echo "<tr>
                <td class='rating_author'>{$rating['author']}</td>
                <td class='rating_text'>{$rating['text']}</td>
                <td class='rating_stars'>{$rating['stars']}</td>
              </tr>";
    }
    ?>
    </tbody>
</table>

<h2><?php echo $texts[$lang]['allergens']; ?></h2>
<ul>
    <?php
    foreach ($meal['allergens'] as $allergenId) {
        if (isset($allergens[$allergenId])) {
            echo "<li>{$allergens[$allergenId]}</li>";
        }
    }
    ?>
</ul>

</body>
</html>
