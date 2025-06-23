
<?php
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */

include 'm2_6a_standardparameter.php';

function multiplizieren($a, $b) {
    return $a * $b;
}

// Check if form inputs exist and set default values if not
if (isset($_POST['a'])) {
    $a = (float)$_POST['a'];
} else {
    $a = 0;
}

if (isset($_POST['b'])) {
    $b = (float)$_POST['b'];
} else {
    $b = 0;
}

$result = "";

// Check which button was clicked to determine the operation
if (isset($_POST['add'])) {
    $result = "Addition Result: " . addieren($a, $b);
} elseif (isset($_POST['multiply'])) {
    $result = "Multiplication Result: " . multiplizieren($a, $b);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Addition and Multiplication Form</title>
</head>
<body>

<h2>Enter values for a and b</h2>

<form method="post">
    <label for="a">a:</label>
    <input type="text" id="a" name="a" value="<?= htmlspecialchars($a) ?>"><br><br>

    <label for="b">b:</label>
    <input type="text" id="b" name="b" value="<?= htmlspecialchars($b) ?>"><br><br>

    <button type="submit" name="add">Addieren</button>
    <button type="submit" name="multiply">Multiplizieren</button>
</form>

<?php
if ($result !== "") {
    echo "<h3>$result</h3>";
}
?>

</body>
</html>