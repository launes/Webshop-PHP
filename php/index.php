<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Space Shop</title>
    <link rel="stylesheet" href="../css/index_stylesheet.css">
</head>
<body>

    <!-- Header-Bereich mit Logo, navigationleiste Warenkorb, Account und so, header befindet sich modular in header.php -->
   <?php 
        include("header.php");
   ?>








    <!-- Startseitenbild mit Text -->
    <section id="hero-image">
        <h1>Mögliche Überschrift und hero image</h1>
        <!-- Hier muss noch ein entsprechendes Hero-img eingefügt werden oder eine stylische überschrift mit CSS -->
    </section>

    <!-- Produktübersicht mit gridlayout, produkte dienen nur als platzhalter damit an css gearbeitet werden kann,
         Produkte werden später mittels PHP-foreach aus der Datenbank abgefragt und entsprechend im grid erstellt -->
    <main>
        <section id="product-grid">
            <!-- Produkt 1 -->
            <article class="product">
                <img src="../images/1.png" alt="#">
                <h2>Nike Airforce</h2>
                <p>€90.00</p>
            </article>

            <!-- Produkt 2 -->
            <article class="product">
                <img src="../images/2.png" alt="#">
                <h2>Adidas Stan Smith</h2>
                <p>€60.00</p>
            </article>

            <!-- Produkt 3 -->
            <article class="product">
                <img src="../images/3.png" alt="#">
                <h2>Air Jordan</h2>
                <p>€120.00</p>
            </article>

            <!-- Produkt 4 -->
            <article class="product">
                <img src="../images/4.png" alt="#">
                <h2>Nike TN</h2>
                <p>€180.00</p>
            </article>

            
        </section>
    </main>

    <!-- footer-Bereich, footer ausgelagert in footer.php -->
    <?php 
        include("footer.php");
    ?>

</body>
</html>
