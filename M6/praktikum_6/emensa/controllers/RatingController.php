<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/benutzer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/bewertung.php');

class RatingController {
    public function showRatingForm() {
        $gericht_id = $_GET['gericht_id'] ?? null;

        if (!$gericht_id) {
            $_SESSION['error'] = "Gericht nicht gefunden.";
            header("Location: /error");
            exit;
        }

        // Fetch the dish
        $gericht = getGerichtById($gericht_id);

        if (!$gericht) {
            $_SESSION['error'] = "Das Gericht wurde nicht gefunden.";
            header("Location: /error");
            exit;
        }
        return view('examples.pages.rating', ['gericht' => $gericht]);
    }

    public function submitRating() {
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bitte melden Sie sich an, um eine Bewertung abzugeben.";
            header("Location: /anmeldung");
            exit;
        }

        // Validate POST data
        if (!isset($_POST['gericht_id'], $_POST['sterne'], $_POST['bemerkung'])) {
            $_SESSION['error'] = "Ungültige Eingabe.";
            header("Location: /bewertung");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $gerichtId = $_POST['gericht_id'];
        $sterne = $_POST['sterne'];
        $bemerkung = trim($_POST['bemerkung']);

        // Validate input
        if (strlen($bemerkung) < 5) {
            $_SESSION['error'] = "Die Bemerkung muss mindestens 5 Zeichen lang sein.";
            header("Location: /bewertung?gerichtid=$gerichtId");
            exit;
        }

        if (!in_array($sterne, ['sehr gut', 'gut', 'schlecht', 'sehr schlecht'])) {
            $_SESSION['error'] = "Ungültige Sternebewertung.";
            header("Location: /bewertung?gerichtid=$gerichtId");
            exit;
        }

        try {
            $link = connectdb();
            $stmt = $link->prepare("
            INSERT INTO Bewertung (benutzer_id, gericht_id, bemerkung, sterne, bewertungszeitpunkt)
            VALUES (?, ?, ?, ?, NOW())
        ");
            $stmt->bind_param("iiss", $userId, $gerichtId, $bemerkung, $sterne);
            $stmt->execute();

            $_SESSION['success'] = "Ihre Bewertung wurde erfolgreich gespeichert.";
            header("Location: /bewertungen");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = "Fehler beim Speichern der Bewertung: " . $e->getMessage();
            header("Location: /bewertung?gerichtid=$gerichtId");
            exit;
        } finally {
            if (isset($link)) {
                $link->close();
            }
        }
    }

    public function showAllRatings() {
        $ratings = getLast30Ratings();
        return view('examples.pages.bewertungen', ['ratings' => $ratings]);
    }

    public function showUserRatings() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bitte melden Sie sich an, um Ihre Bewertungen zu sehen.";
            header("Location: /anmeldung");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $ratings = getUserRatings($userId);

        return view('examples.pages.meinebewertungen', ['ratings' => $ratings]);
    }

    public function deleteRating() {
        session_start();
        $userId = $_SESSION['user_id'] ?? null; // Assuming user session has their ID
        $ratingId = $_GET['id'] ?? null; // Get rating ID from query string

        if (!$userId) {
            $_SESSION['error'] = "Bitte melden Sie sich an.";
            header("Location: /anmeldung");
            exit;
        }

        if (!$ratingId) {
            $_SESSION['error'] = "Bewertung nicht gefunden.";
            header("Location: /meinebewertungen");
            exit;
        }

        try {
            $link = connectdb();
            $stmt = $link->prepare("
            DELETE FROM bewertung
            WHERE id = ? AND benutzer_id = ?
        ");
            $stmt->bind_param("ii", $ratingId, $userId);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $_SESSION['success'] = "Die Bewertung wurde erfolgreich gelöscht.";
            } else {
                $_SESSION['error'] = "Löschen der Bewertung fehlgeschlagen.";
            }

            $stmt->close();
        } catch (Exception $e) {
            $_SESSION['error'] = "Fehler beim Löschen: " . $e->getMessage();
        } finally {
            if (isset($link)) {
                $link->close();
            }
        }

        header("Location: /meinebewertungen");
        exit;
    }
    public function highlightRating() {
        session_start();

        logger()->info('Highlighting Rating Request Received', [
            'user_id' => $_SESSION['user_id'] ?? null,
            'admin' => $_SESSION['admin'] ?? null,
            'rating_id' => $_GET['id'] ?? null,
        ]);

        if (empty($_SESSION['user_id']) || empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {
            $_SESSION['error'] = "Nur Administratoren können Bewertungen hervorheben.";
            header("Location: /bewertungen");
            exit;
        }

        $ratingId = $_GET['id'] ?? null;

        if (!$ratingId) {
            $_SESSION['error'] = "Bewertung nicht gefunden.";
            logger()->warning('Highlighting Failed: Missing Rating ID', [
                'rating_id' => $ratingId,
            ]);
            header("Location: /bewertungen");
            exit;
        }

        // Existing functionality to highlight the rating
        if (highlightRating($ratingId)) {
            $_SESSION['success'] = "Die Bewertung wurde hervorgehoben.";
            logger()->info('Highlighting Successful', ['rating_id' => $ratingId]);
        } else {
            $_SESSION['error'] = "Fehler beim Hervorheben.";
            logger()->error('Highlighting Failed: Database Update Error', ['rating_id' => $ratingId]);
        }

        header("Location: /bewertungen");
        exit;
    }

    public function showHighlightedRatings() {
        $ratings = getHighlightedRatings();
        return view('examples.pages.hervorgehobene_bewertungen', ['ratings' => $ratings]);
    }
    public function unhighlightRating() {
        session_start();

        // Check if the user is an admin
        if (empty($_SESSION['user_id']) || empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {
            $_SESSION['error'] = "Nur Administratoren können Hervorhebungen abwählen.";
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
            $stmt = $link->prepare("UPDATE bewertung SET highlighted = 0 WHERE id = ?");
            $stmt->bind_param("i", $ratingId);

            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                // Optionally log but do not show an error
                logger()->info("Unhighlighting skipped: Rating not highlighted or does not exist.", ['rating_id' => $ratingId]);
            } else {
                $_SESSION['success'] = "Die Hervorhebung wurde abgewählt.";
            }

            $stmt->close();
            $link->close();

        } catch (Exception $e) {
            $_SESSION['error'] = "Fehler beim Abwählen der Hervorhebung.";
        }

        // Redirect back to the ratings page
        header("Location: /bewertungen");
        exit;
    }

}
