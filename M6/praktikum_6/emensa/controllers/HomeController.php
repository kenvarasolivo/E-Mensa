<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/allergen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/bewertung.php');
/* Datei: controllers/HomeController.php */
class HomeController
{
    public function index(RequestData $request) {
        return view('home', ['rd' => $request]);
    }

    public function debug(RequestData $request) {
        return view('debug');
    }

    public function werbeseite(RequestData $request) {
        logger()->info('Hauptseite wurde aufgerufen.');

        $sortOrder = $request->query['sort'] ?? 'asc'; // Get sort order from query parameters

        // Get sorted and limited dishes (combined fetching, sorting, and limiting)
        $sortedGerichte = get_sorted_and_limited_gerichte($sortOrder);

        // Fetch all allergens grouped by dish
        $allergeneByGericht = db_get_allergene_by_gericht();

        // Format allergens for the displayed dishes
        $sortedGerichte = format_allergens_for_gerichte($sortedGerichte, $allergeneByGericht);

        // Generate allergen details for the displayed dishes
        $filteredAllergeneByGericht = get_filtered_allergene_by_gerichte($sortedGerichte, $allergeneByGericht);
        $allergenDetails = get_allergen_details($filteredAllergeneByGericht);

        // Fetch highlighted ratings
        $highlightedRatings = getHighlightedRatings(); // Function from bewertung.php

        // Pass processed data to the view
        return view('examples.pages.werbeseite', [
            'gerichte' => $sortedGerichte,
            'allergenDetails' => $allergenDetails,
            'highlightedRatings' => $highlightedRatings
        ]);
    }
}
