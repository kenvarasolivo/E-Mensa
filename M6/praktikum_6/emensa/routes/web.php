<?php
/**
 * Mapping of paths to controllers.
 * Note, that the path only supports one level of directory depth:
 *     /demo is ok,
 *     /demo/subpage will not work as expected
 */

return array(
    '/anmeldung' => "UserController@showLoginForm",
    '/anmeldung_verifizieren' => "UserController@verifyLogin",
    '/abmeldung' => "UserController@logout",

    '/bewertung' => "RatingController@showRatingForm",
    '/submit_rating' => "RatingController@submitRating",
    '/bewertungen' => "RatingController@showAllRatings",
    '/meinebewertungen' => "RatingController@showUserRatings",
    '/loeschen' => "RatingController@deleteRating",
    '/hervorheben' => "RatingController@highlightRating",
    '/hervorgehobene-bewertungen' => "RatingController@showHighlightedRatings",
    '/abwaehlen' => 'RatingController@unhighlightRating',

    '/'             => "HomeController@werbeseite",
    "/demo"         => "DemoController@demo",
    '/dbconnect'    => 'DemoController@dbconnect',
    '/debug'        => 'HomeController@debug',
    '/error'        => 'DemoController@error',
    '/requestdata'   => 'DemoController@requestdata',

    // Erstes Beispiel:
    '/m4_6a_queryparameter' => 'ExampleController@m4_6a_queryparameter',
    '/m4' => 'ExampleController@m4_6a_queryparameter',

    // Aufgabe 7
    '/m4_7a_queryparameter' => 'ExampleController@m4_7a_queryparameter',
    '/m4_7b_kategorie' => 'ExampleController@m4_7b_kategorie',
    '/m4_7c_gerichte' => 'ExampleController@m4_7c_gerichte',
    '/m4_7d_layout' => 'ExampleController@m4_7d_layout',

    '/wunschgericht' => 'HomeController@wunschgericht',
);