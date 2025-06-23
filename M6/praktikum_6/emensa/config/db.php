<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
return [

    'host' => 'localhost',  // 'localhost' or '127.0.0.1'
    'user' => 'root',       // '<yourusername>'
    'password' => 'root', // '<yourpassword>'
    'database' => 'emensawerbeseite',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    // optionally: set port below if it differs from the default 3306
    // 'port' => 13306 // !! this is not your webserver port, but the mariadb port
];
