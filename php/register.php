<?php
// Session starten, damit wir Daten über mehrere Seiten speichern können
session_start();

// Die Datei mit der Datenbankverbindung einbinden / __DIR__ ist der Pfad zu diesem Skript
require_once __DIR__ . '/db_connect.php';

// Ein leeres Array für Fehlermeldungen anlegen
$fehlermeldungen = [];

// Prüfen, ob das Formular abgeschickt wurde (also ob ein POST-Request vorliegt)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verbindung zur Datenbank aufbauen
    $verbindung = erstelleDatenbankverbindung();

    // Benutzername aus dem Formular holen und "säubern"
    $benutzername = htmlspecialchars(trim($_POST['benutzername'] ?? ''));
    // E-Mail aus dem Formular holen und "säubern"
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    // Passwort aus dem Formular holen
    $passwort = $_POST['passwort'] ?? '';
    // Passwort-Wiederholung aus dem Formular holen
    $passwortBestaetigen = $_POST['passwort_bestaetigen'] ?? '';

    // Prüfen, ob der Benutzername leer ist
    if (empty($benutzername)) { // Wenn kein Benutzername eingegeben wurde
        $fehlermeldungen[] = "Bitte gib einen Benutzernamen ein!"; // Fehlermeldung hinzufügen
    }
    // Prüfen, ob die E-Mail gültig ist
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Wenn die E-Mail nicht dem Standard entspricht
        $fehlermeldungen[] = "Bitte gib eine gültige E-Mail-Adresse ein!"; // Fehlermeldung hinzufügen
    }
    // Prüfen, ob das Passwort lang genug ist
    if (strlen($passwort) < 8) { // Wenn das Passwort weniger als 8 Zeichen hat
        $fehlermeldungen[] = "Das Passwort muss mindestens 8 Zeichen lang sein!"; // Fehlermeldung hinzufügen
    }
    // Prüfen, ob beide Passwörter gleich sind
    if ($passwort !== $passwortBestaetigen) { // Wenn die Passwörter unterschiedlich sind
        $fehlermeldungen[] = "Die Passwörter stimmen nicht überein!"; // Fehlermeldung hinzufügen
    }

    // Nur weitermachen, wenn keine Fehler aufgetreten sind
    if (empty($fehlermeldungen)) { // Wenn das Array mit Fehlermeldungen leer ist
        // Datenbankabfrage vorbereiten, um zu prüfen, ob es den Benutzer schon gibt / selektiert die ID wo E-Mail oder Benutzername übereinstimmt
        $abfrage = $verbindung->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        // Die Eingaben an die Abfrage binden / "ss" steht für "string, string"
        $abfrage->bind_param("ss", $email, $benutzername);
        // Die Abfrage ausführen / execute() führt die vorbereitete Abfrage aus
        $abfrage->execute();
        // Das Ergebnis "puffern", damit wir die Anzahl der gefundenen Zeilen abfragen können / store_result() speichert das Ergebnis der Abfrage in der Abfrage-Variable
        $abfrage->store_result();

        // Prüfen, ob schon ein Benutzer mit dieser E-Mail oder diesem Benutzernamen existiert
        if ($abfrage->num_rows > 0) { // Wenn mindestens ein Eintrag gefunden wurde
            $fehlermeldungen[] = "Benutzername oder E-Mail ist schon vergeben!"; // Fehlermeldung hinzufügen
        }
        // Die Abfrage schließen
        $abfrage->close();
    }

    // Wenn immer noch keine Fehler aufgetreten sind, neuen Benutzer anlegen
    if (empty($fehlermeldungen)) { // Wenn das Array mit Fehlermeldungen immer noch leer ist
        // Passwort sicher verschlüsseln (hashen)
        $passwortHash = password_hash($passwort, PASSWORD_DEFAULT);

        // Neue Datenbankabfrage zum Einfügen des Benutzers vorbereiten / prepare() bereitet die SQL-Abfrage vor, ohne sie auszuführen
        $einfuegen = $verbindung->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        // Die Werte an die Abfrage binden / "sss" steht für "string, string, string" (Benutzername, E-Mail, Passwort)
        $einfuegen->bind_param("sss", $benutzername, $email, $passwortHash);

        // Die Abfrage ausführen / execute() führt die vorbereitete Abfrage aus
        if ($einfuegen->execute()) { // Wenn das Einfügen geklappt hat
            $_SESSION['erfolgsmeldung'] = "Registrierung erfolgreich! Du kannst dich jetzt einloggen."; // Erfolgsmeldung speichern
            header("Location: login.php"); // Zur Login-Seite weiterleiten
            exit(); // Skript beenden
        } else { // Wenn das Einfügen nicht geklappt hat
            $fehlermeldungen[] = "Fehler bei der Registrierung: " . $einfuegen->error; // Fehlermeldung hinzufügen
        }
        // Die Einfüge-Abfrage schließen
        $einfuegen->close();
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Registrierung</title>
    <?php include 'head.php';?> <!-- Header einbinden -->
    <link rel="stylesheet" href="../css/style_register.css"> <!-- Optional: CSS-Datei für das Styling -->
</head>
<body>
<h2>Registrierung</h2>
<!-- Fehlermeldungen anzeigen, falls vorhanden -->
<?php if (!empty($fehlermeldungen)): ?>
    <div style="color:red;">
        <?php foreach ($fehlermeldungen as $meldung): ?>
            <p><?= htmlspecialchars($meldung) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<!-- Das Registrierungsformular -->
<form method="POST">
    <!-- Benutzername-Eingabe -->
    <label>
        <input type="text" name="benutzername" placeholder="Benutzername" required
               value="<?= htmlspecialchars($_POST['benutzername'] ?? '') ?>">
    </label><br>
    <!-- E-Mail-Eingabe -->
    <label>
        <input type="email" name="email" placeholder="E-Mail" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label><br>
    <!-- Passwort-Eingabe -->
    <label>
        <input type="password" name="passwort" placeholder="Passwort" required>
    </label><br>
    <!-- Passwort-Bestätigung -->
    <label>
        <input type="password" name="passwort_bestaetigen" placeholder="Passwort bestätigen" required>
    </label><br>
    <!-- Button zum Abschicken des Formulars -->
    <button type="submit">Registrieren</button>
</form>
<p>Bereits registriert? <a href="login.php">Zum Login</a></p>
</body>
<?php include 'footer.php';?> <!-- Footer einbinden -->
</html>
