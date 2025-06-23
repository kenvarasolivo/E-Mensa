<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "gerichte"
 */
function db_gericht_select_all() {
    try {
        $link = connectdb();

        $sql = 'SELECT id, name, beschreibung, bildname FROM gericht ORDER BY name'; // Add bildname to the SELECT statement
        $result = mysqli_query($link, $sql);

        $data = mysqli_fetch_all($result, MYSQLI_BOTH);

        mysqli_close($link);
    } catch (Exception $ex) {
        $data = array(
            'id' => '-1',
            'error' => true,
            'name' => 'Datenbankfehler ' . $ex->getCode(),
            'beschreibung' => $ex->getMessage()
        );
    } finally {
        return $data;
    }
}

function db_gericht_select_above_2() {
    try {
        $link = connectdb();

        $sql = 'SELECT id, name, beschreibung, preisintern, bildname FROM gericht WHERE preisintern > 2 ORDER BY name DESC'; // Add bildname to the SELECT statement
        $result = mysqli_query($link, $sql);

        $data = mysqli_fetch_all($result, MYSQLI_BOTH);

        mysqli_close($link);
    } catch (Exception $ex) {
        $data = array(
            'id' => '-1',
            'error' => true,
            'name' => 'Datenbankfehler ' . $ex->getCode(),
            'beschreibung' => $ex->getMessage()
        );
    } finally {
        return $data;
    }
}
function getgericht(): array {
    $link = connectdb();
    $sql = "SELECT id, name, preisintern, preisextern, bildname FROM gericht ORDER BY name"; // Add bildname to the SELECT statement
    $result = mysqli_query($link, $sql);

    $gerichte = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $gerichte[] = $row;
    }

    mysqli_free_result($result);
    mysqli_close($link);

    return $gerichte;
}



function get_sorted_and_limited_gerichte($sortOrder = 'asc', $limit = null) {
    $gerichte = getgericht(); // Fetch all dishes.

    // Sort the array based on the sort order.
    usort($gerichte, function ($a, $b) use ($sortOrder) {
        return $sortOrder === 'desc'
            ? strcmp($b['name'], $a['name']) // Descending order.
            : strcmp($a['name'], $b['name']); // Ascending order.
    });

    // Limit the array if $limit is provided.
    if ($limit !== null) {
        $gerichte = array_slice($gerichte, 0, $limit);
    }

    return $gerichte; // Return the sorted and limited array.
}
function get_filtered_allergene_by_gerichte($gerichte, $allergeneByGericht) {
    $filteredAllergeneByGericht = [];
    foreach ($gerichte as $gericht) {
        if (isset($allergeneByGericht[$gericht['id']])) {
            $filteredAllergeneByGericht[$gericht['id']] = $allergeneByGericht[$gericht['id']];
        }
    }
    return $filteredAllergeneByGericht;
}
function format_allergens_for_gerichte($gerichte, $allergeneByGericht) {
    foreach ($gerichte as &$gericht) {
        if (isset($allergeneByGericht[$gericht['id']]) && count($allergeneByGericht[$gericht['id']]) > 0) {
            $gericht['formatted_allergens'] = implode(
                ', ',
                array_unique(array_column($allergeneByGericht[$gericht['id']], 'code'))
            );
        } else {
            $gericht['formatted_allergens'] = 'Keine Allergene';
        }
    }
    return $gerichte;
}

function getGerichtById($gericht_id) {
    $link = connectdb();
    $stmt = $link->prepare("SELECT * FROM gericht WHERE id = ?");
    $stmt->bind_param("i", $gericht_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


