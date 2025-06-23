<?php
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */

$file = 'en.txt';
$searchWord = $_GET['suche'] ?? '';

if (empty($searchWord)) {
    echo "Bitte geben Sie ein Suchwort ein.";
} else {
    $fileContent = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $found = false;

    foreach ($fileContent as $line) {
        list($germanWord, $englishWord) = explode(';', $line);
        if (strtolower($germanWord) === strtolower($searchWord)) {
            echo "Übersetzung für $searchWord : $englishWord";
            $found = true;
            break;
        }
    }

    if (!$found) {
        echo "Das gesuchte Wort '$searchWord' ist nicht enthalten.";
    }
}
?>
