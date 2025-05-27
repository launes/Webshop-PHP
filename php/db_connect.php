<?php
// Funktion zum Herstellen einer Datenbankverbindung
function erstelleDatenbankverbindung() {
    // Hostname des Datenbankservers (bei XAMPP meistens "localhost")
    $servername = "localhost";
    // Benutzername für die Datenbank (bei XAMPP meistens "root")
    $username = "root";
    // Passwort für die Datenbank (bei XAMPP meistens leer)
    $password = "";
    // Name der Datenbank, die verwendet werden soll
    $dbname = "amazon_clone";

    // Erstellen eines neuen MySQLi-Objekts für die Verbindung
    $verbindung = new mysqli($servername, $username, $password, $dbname);

    // Prüfen, ob die Verbindung geklappt hat
    if ($verbindung->connect_error) { // Wenn ein Verbindungsfehler aufgetreten ist
        die("Verbindung fehlgeschlagen: " . $verbindung->connect_error); // Skript beenden und Fehlermeldung anzeigen
    }

    // Zeichensatz der Verbindung auf UTF-8 setzen (wichtig für Umlaute)
    $verbindung->set_charset("utf8");

    // Die hergestellte Verbindung zurückgeben
    return $verbindung;
}
?>
