<?php
// Funktion zum Herstellen einer Datenbankverbindung
function erstelleDatenbankverbindung() {
    // Umgebungsvariablen laden
    // getenv() holt den Wert der Umgebungsvariablen, falls sie gesetzt ist
    // Wenn die Umgebungsvariable nicht gesetzt ist, wird ein Standardwert verwendet
    $servername = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';
    $dbname = getenv('DB_NAME') ?: 'amazon_clone';

    // Erstellen eines neuen MySQLi-Objekts für die Verbindung
    // mysqli ist eine PHP-Erweiterung, die für den Zugriff auf MySQL-Datenbanken verwendet wird
    $verbindung = new mysqli($servername, $username, $password, $dbname);

    // Prüfen, ob die Verbindung geklappt hat
    // connect_error prüft, ob ein Fehler bei der Verbindung aufgetreten ist
    if ($verbindung->connect_error) {
        error_log("Datenbankverbindung fehlgeschlagen: " . $verbindung->connect_error);
        die("Datenbankverbindung konnte nicht hergestellt werden. Bitte versuchen Sie es später erneut.");
    }

    // Zeichensatz der Verbindung auf UTF-8 setzen
    // set_charset() setzt den Zeichensatz der Verbindung, um sicherzustellen, dass Sonderzeichen korrekt behandelt werden
    $verbindung->set_charset("utf8");

    return $verbindung;
}
?>
