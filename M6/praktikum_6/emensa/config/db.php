<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
return [
    'host' => 'localhost',
    'user' => '<your_db_user>',
    'password' => '<your_db_password>',
    'database' => 'emensawerbeseite',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
