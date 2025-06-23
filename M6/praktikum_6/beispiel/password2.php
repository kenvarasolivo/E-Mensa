<?php

$salt = 'E-MensaSalt123'; // Use the same salt as before
$newPassword = 'user'; // Replace with your desired new password

$hashedPassword = hash('sha256', $salt . $newPassword);

echo "New Hashed Password: " . $hashedPassword;