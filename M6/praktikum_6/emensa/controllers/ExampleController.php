<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/kategorie.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');

class ExampleController
{
    public function m4_6a_queryparameter(RequestData $rd) {
        /*
           Wenn Sie hier landen:
           bearbeiten Sie diese Action,
           so dass Sie die Aufgabe löst
        */

        return view('notimplemented', [
            'request'=>$rd,
            'url' => 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"
        ]);
    }

    public function m4_7a_queryparameter(RequestData $rd) {
        $name = $rd->query['name'] ?? 'Unbekannt';
        return view('examples.m4_7a_queryparameter', ['name' => $name]);
    }

    public function m4_7b_kategorie(RequestData $rd){
        $categories = db_kategorie_select_all();
        return view('examples.m4_7b_kategorie', ['categories' => $categories]);
    }

    public function m4_7c_gerichte(RequestData $rd) {
        // Fetch dishes with price greater than 2€
        $gerichte = db_gericht_select_above_2();

        // If no dishes are found, set a message
        if (empty($gerichte)) {
            $message = "Es sind keine Gerichte vorhanden";
        } else {
            $message = null;
        }

        return view('examples.m4_7c_gerichte', [
            'gerichte' => $gerichte,
            'message' => $message
        ]);
    }

    public function m4_7d_layout(RequestData $rd) {
        $page = $rd->query['no'] ?? 1;
        $view = $page == 2 ? 'examples.pages.m4_7d_page_2' : 'examples.pages.m4_7d_page_1';

        // Return the chosen view with the title
        return view($view);
    }
}