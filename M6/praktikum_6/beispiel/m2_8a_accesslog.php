<?php
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 * Hanif, Ibrahim, 3657278
 */

$logFile = 'accesslog.txt';

$datetime = date('Y-m-d H:i:s');
$ipAddr = $_SERVER['REMOTE_ADDR'] ?? 'Unbekannte IP';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unbekannter Browser';

$logEntry = "$datetime - IP: $ipAddr - Browser: $userAgent" . PHP_EOL;
file_put_contents($logFile, $logEntry, FILE_APPEND);

echo "Der Zugriff wurde protokolliert.";
?>