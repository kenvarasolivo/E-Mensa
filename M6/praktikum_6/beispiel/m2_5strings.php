<?php
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */
// m2_5_strings.php

// a.
$text = "Hello world!";
$new_text = str_replace("world", "PHP", $text);
echo "Original Text: " . $text . "<br>";
echo "Modified Text: " . $new_text ."<br>";

// b.
$text2 = "Hello world!";
$new_text2 = str_repeat($text2,3);
echo "Original Text: " . $text2 . "<br>";
echo "Modified Text: " . $new_text2. "<br>";

// c.
$text3 = "Hello world!";
$new_text3 = substr($text3,3);
$new1_text3 = substr($text3,3, 4);

echo "Offset3 Text: " . $new_text3 . "<br>";
echo "Offset3 Lenght4 Text: " . $new1_text3. "<br>";

// d.
$text = "   Hello, world!   ";
$trimmedText = trim($text);
$rtrimmedText = rtrim($text);
$ltrimmedText = ltrim($text);
echo "<pre>Original text: '$text' </pre>";
echo "<pre>Trimmed: '$trimmedText'</pre>";
echo "<pre>Left Trimmed: '$ltrimmedText'</pre>";
echo "<pre>Right Trimmed: '$rtrimmedText'</pre>";

// e.
$greeting = "Hello";
$name = "world";
$fullGreeting = $greeting . ", " . $name . "!";
echo $fullGreeting;