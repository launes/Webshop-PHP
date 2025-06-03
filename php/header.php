<?php
// Überprüfen, ob die Session gestartet ist, und falls nicht, sie starten
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <?php
    // Überprüfen, ob der Benutzer eingeloggt ist
    if (isset($_SESSION['benutzername'])) {
        echo '<span>Willkommen, ' . htmlspecialchars($_SESSION["benutzername"]) . '!</span> '; // Begrüßung mit Benutzernamen
        echo '<form action="./logout.php" method="post" style="display:inline;"> 
                <button type="submit">Logout</button>
              </form>';
    } else { // Wenn der Benutzer nicht eingeloggt ist
        echo '<span>Willkommen, Gast!</span> '; // Begrüßung für Gäste
        echo '<a href="./login.php">Login</a>'; // Link zum Login
    }
    ?>
    <div id="logo">SHOP LOGO PLATZHALTER</div>
    <nav>
        <ul>
            <li><a href="#">Männer</a></li>
            <li><a href="#">Frauen</a></li>
            <li><a href="#">Kinder</a></li>
        </ul>
    </nav>
    <div id="user-section">
        <a href="#">Suche</a>
        <a href="#">Account</a>
        <a href="#">Warenkorb (0)</a>
    </div>
</header>
