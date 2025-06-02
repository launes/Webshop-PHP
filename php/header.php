<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<head>
    <link rel="stylesheet" href="../css/style_header.css">
</head>
<header>
    <?php
    if (isset($_SESSION['benutzername'])) {
        echo '<span>Willkommen, ' . htmlspecialchars($_SESSION["benutzername"]) . '!</span> ';
        echo '<form action="./logout.php" method="post" style="display:inline;">
                <button type="submit">Logout</button>
              </form>';
    } else {
        echo '<span>Willkommen, Gast!</span> ';
        echo '<a href="./login.php">Login</a>';
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
