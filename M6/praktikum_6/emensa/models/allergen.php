<?php
function get_all_allergens() {
    try {
        $link = connectdb();

        $sql = "SELECT code, name, typ FROM allergen ORDER BY typ, name";
        $result = mysqli_query($link, $sql);

        $allergens = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_close($link);
        return $allergens;

    } catch (Exception $ex) {
        return []; // Return an empty array on failure
    }
}
function db_get_allergene_by_gericht() {
    try {
        $link = connectdb();

        // Ensure no duplicates by selecting DISTINCT
        $sql = "
            SELECT DISTINCT g.id AS gericht_id, a.code, a.name, a.typ
            FROM gericht g
            LEFT JOIN gericht_hat_allergen gha ON g.id = gha.gericht_id
            LEFT JOIN allergen a ON gha.code = a.code
            ORDER BY g.id, a.typ, a.name
        ";
        $result = mysqli_query($link, $sql);

        $allergeneByGericht = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $gerichtId = $row['gericht_id'];
            if (!isset($allergeneByGericht[$gerichtId])) {
                $allergeneByGericht[$gerichtId] = [];
            }
            $allergeneByGericht[$gerichtId][] = [
                'code' => $row['code'],
                'name' => $row['name'],
                'typ' => $row['typ']
            ];
        }

        mysqli_close($link);
        return $allergeneByGericht;

    } catch (Exception $ex) {
        return []; // Return an empty array in case of failure
    }
}
function get_allergen_details($allergeneByGericht) {
    $allergensList = [];
    foreach ($allergeneByGericht as $gerichtId => $allergens) {
        foreach ($allergens as $allergen) {
            if (!empty($allergen['code'])) {
                $name = htmlspecialchars($allergen['name'] ?? '');
                $allergensList[$allergen['code']] = $name;
            }
        }
    }
    return $allergensList;
}
?>