<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Famous Meals and Winning Years</title>
    <style>
        /* Minimal styling for spacing */
        ol {
            margin-bottom: 20px;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php
// Initialize the array with meal names and winning years
$famousMeals = [
    1 => [
        'name' => 'Currywurst mit Pommes',
        'winner' => [2020, 2010, 2007, 2003, 2001]
    ],
    2 => [
        'name' => 'Hähnchencrossies mit Paprikareis',
        'winner' => [2008, 2004, 2002]
    ],
    3 => [
        'name' => 'Spaghetti Bolognese',
        'winner' => [2017, 2012, 2011]
    ],
    4 => [
        'name' => 'Jägerschnitzel mit Pommes',
        'winner' => [2019]
    ]
];

// Function to find years without winners from 2000 to the current year
function findYearsWithoutWinners($meals) {
    $startYear = 2000;
    $endYear = date("Y"); // Current year
    $winningYears = [];

    // Collect all winning years across all meals
    foreach ($meals as $meal) {
        foreach ($meal['winner'] as $year) {
            $winningYears[] = $year;
        }
    }

    // Find missing years by checking each year from 2000 to the current year
    $missingYears = [];
    for ($year = $startYear; $year <= $endYear; $year++) {
        if (!in_array($year, $winningYears)) {
            $missingYears[] = $year;
        }
    }

    return $missingYears;
}

// Display the list of meals with sorted winning years
echo "<ol>";
foreach ($famousMeals as $meal) {
    // Sort the winner years in descending order (most recent first)
    rsort($meal['winner']);

    // Display the meal name
    echo "<li><strong>" . htmlspecialchars($meal['name']) . "</strong><br>";

    // Display the years it won, separated by commas
    echo implode(', ', $meal['winner']);
    echo "</li>";
}
echo "</ol>";

// Find and display years without winners
$missingYears = findYearsWithoutWinners($famousMeals);
echo "<h2>Years Without Winners (2000 to " . date("Y") . ")</h2>";
echo "<p>" . implode(', ', $missingYears) . "</p>";
?>
</body>
</html>