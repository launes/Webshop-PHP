<?php
// Funktion zum Herstellen einer Datenbankverbindung
function erstelleDatenbankverbindung() {
    // Umgebungsvariablen laden
    $servername = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';
    $dbname = getenv('DB_NAME') ?: 'amazon_clone';

    // Erstellen eines neuen MySQLi-Objekts für die Verbindung
    $verbindung = new mysqli($servername, $username, $password, $dbname);

    // Prüfen, ob die Verbindung geklappt hat
    if ($verbindung->connect_error) {
        error_log("Datenbankverbindung fehlgeschlagen: " . $verbindung->connect_error);
        die("Datenbankverbindung konnte nicht hergestellt werden. Bitte versuchen Sie es später erneut.");
    }

    // Zeichensatz der Verbindung auf UTF-8 setzen
    $verbindung->set_charset("utf8");

    return $verbindung;
}
?>
