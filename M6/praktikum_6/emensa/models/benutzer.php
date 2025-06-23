<?php

function getUserByEmail($email) {
    $link = connectdb();
    $stmt = $link->prepare("SELECT * FROM benutzer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    $link->close();

    return $user;
}

function updateLastError($userId) {
    $link = connectdb();
    $stmt = $link->prepare("UPDATE benutzer SET letzterfehler = NOW(), anzahlfehler = anzahlfehler + 1 WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $stmt->close();
    $link->close();
}

function updateLastLogin($userId) {
    $link = connectdb();
    $stmt = $link->prepare("UPDATE benutzer SET letzteanmeldung = NOW() WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $stmt->close();
    $link->close();
}

function incrementLoginCounter($userId) {
    $link = connectdb();
    $stmt = $link->prepare("CALL IncrementAnmeldung(?)");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $stmt->close();
    $link->close();
}
