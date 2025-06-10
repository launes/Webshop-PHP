<?php
// Funktion zum Herstellen einer Datenbankverbindung
function erstelleDatenbankverbindung() {
    // Umgebungsvariablen laden
    // getenv() holt den Wert der Umgebungsvariablen, falls sie gesetzt ist
    // Wenn die Umgebungsvariable nicht gesetzt ist, wird ein Standardwert verwendet
    $servername = getenv('DB_HOST') ?: 'db.be-mons1.bengt.wasmernet.com:3306';
    $username = getenv('DB_USER') ?: '2a1dba357c6580004cacf4221acc';
    $password = getenv('DB_PASS') ?: '06842a1d-ba35-7da9-8000-c254363476a9';
    $dbname = getenv('DB_NAME') ?: 'amazon_clone';

    // Erstellen eines neuen MySQLi-Objekts für die Verbindung
    // mysqli ist eine PHP-Erweiterung, die für den Zugriff auf MySQL-Datenbanken verwendet wird
    $verbindung = new mysqli('db.be-mons1.bengt.wasmernet.com:3306', '2a1dba357c6580004cacf4221acc', '06842a1d-ba35-7da9-8000-c254363476a9', 'amazon_clone');

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

