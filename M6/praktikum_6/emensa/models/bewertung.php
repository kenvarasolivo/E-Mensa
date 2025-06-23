<?php

function getLast30Ratings() {
$link = connectdb();
    $stmt = $link->prepare("
        SELECT b.id, b.bemerkung, b.sterne, b.bewertungszeitpunkt, b.highlighted, g.name AS gericht_name
        FROM bewertung b
        JOIN gericht g ON b.gericht_id = g.id
        ORDER BY b.bewertungszeitpunkt DESC
        LIMIT 30
    ");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUserRatings($userId) {
    $link = connectdb();
    $stmt = $link->prepare("
        SELECT b.id, b.bemerkung, b.sterne, b.bewertungszeitpunkt, g.name AS gericht_name
        FROM bewertung b
        JOIN gericht g ON b.gericht_id = g.id
        WHERE b.benutzer_id = ?
        ORDER BY b.bewertungszeitpunkt DESC
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

 function highlightRating() {

    // Check if the user is an admin
    if (empty($_SESSION['user_id']) || empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {
        $_SESSION['error'] = "Nur Administratoren können Bewertungen hervorheben.";
        header("Location: /bewertungen");
        exit;
    }

    // Get the rating ID
    $ratingId = $_GET['id'] ?? null;

    if (!$ratingId) {
        $_SESSION['error'] = "Bewertung nicht gefunden.";
        header("Location: /bewertungen");
        exit;
    }

    try {
        $link = connectdb();
        $stmt = $link->prepare("UPDATE bewertung SET highlighted = 1 WHERE id = ? AND highlighted = 0");
        $stmt->bind_param("i", $ratingId);

        $stmt->execute();

        // If no rows are affected, assume already highlighted or invalid ID
        if ($stmt->affected_rows == 0) {
            // Optionally, log the case but do not show an error
            logger()->info("Highlighting skipped: Rating already highlighted or does not exist.", ['rating_id' => $ratingId]);
        } else {
            $_SESSION['success'] = "Die Bewertung wurde hervorgehoben.";
        }

        $stmt->close();
        $link->close();

    } catch (Exception $e) {
        $_SESSION['error'] = "Fehler beim Hervorheben.";
    }

    // Redirect back to the ratings page
    header("Location: /bewertungen");
    exit;
}



function getHighlightedRatings() {
    $link = connectdb();
    $stmt = $link->prepare("
        SELECT b.id, b.bemerkung, b.sterne, b.bewertungszeitpunkt, g.name AS gericht_name
        FROM bewertung b
        JOIN gericht g ON b.gericht_id = g.id
        WHERE b.highlighted = 1
        ORDER BY b.bewertungszeitpunkt DESC
    ");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}