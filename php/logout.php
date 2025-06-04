<?php
// Session-Start: Bereitet die Nutzung von Session-Variablen vor
session_start();

// Session-Daten leeren: Alle gespeicherten Session-Variablen werden gelöscht
$_SESSION = [];

// Session-Cookie löschen: Das Cookie, das die Session-ID speichert, wird entfernt wenn es verwendet wird
// ini_get("session.use_cookies") prüft, ob Sessions mit Cookies verwendet werden
if (ini_get("session.use_cookies")) {
    // Cookie-Parameter abrufen (Pfad, Domain, Sicherheitseinstellungen etc.)
    // session_get_cookie_params() gibt die aktuellen Cookie-Einstellungen zurück
    // cookie-Parameter enthalten Informationen wie Pfad, Domain, ob es nur über HTTPS gesendet werden soll und ob es nur per HTTP zugänglich ist
    $params = session_get_cookie_params();
    // Cookie mit abgelaufenem Zeitstempel setzen, um es zu löschen
    setcookie(
        session_name(),        // Name des Session-Cookies
        '',                    // leerer Wert
        time() - 420000,       // Ablaufzeitpunkt in der Vergangenheit, damit Cookie gelöscht wird
        $params["path"],       // Cookie-Pfad
        $params["domain"],     // Cookie-Domain
        $params["secure"],     // Nur über HTTPS, falls aktiviert
        $params["httponly"]    // Cookie nur per HTTP, nicht per JavaScript zugänglich
    );
}

// Session beenden: Session wird vollständig zerstört und alle Ressourcen freigegeben
session_destroy();
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <!-- <meta charset="UTF-8"> -->
        <?php include 'head.php';?> <!-- Head einbinden -->
        <title>Logout</title>
        <!-- Automatische Weiterleitung nach 10 Sekunden zur Startseite -->
        <meta http-equiv="refresh" content="10;url=index.php">
        <link rel="stylesheet" href="../css/style_logout.css">
    </head>
    <body>
        <div class="logout-message">
            <h1>Sie wurden ausgeloggt.</h1>
            <!-- Hinweis auf die automatische Weiterleitung -->
            <p>Sie werden in 10 Sekunden automatisch zur Startseite weitergeleitet.</p>
            <!-- Direktlink zur Startseite -->
            <p><a href="../index.php">Jetzt zurück zur Startseite</a></p>
        </div>
    </body>
</html>