<?php
require_once 'head.php';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egyéb információk</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <meta name="description" content="További információk az iskoláról">
</head>

<body>
    <header>
        <nav class="topnav" id="myTopnav">
            <a href="display.php">Adatbázis</a>
            <a href="queries.php">Lekérdezések</a>
            <a href="contact.php">Lépj velünk kapcsolatba</a>
            <a class="active" href="info.php">Egyéb információk</a>
            <a href="admin.php">Admin</a>
            
            <a href="javascript:void(0);" class="icon" onclick="myFunction()" aria-label="Menü megnyitása">

                <i class="fa fa-bars"></i>
            </a>
        </nav>
    </header>

    <main class="content">
        <section aria-labelledby="info-title">
            <h2 id="info-title">Egyéb információk</h2>
            
            <div class="info-section">
                <h3>Iskolánk története</h3>
                <p>Iskolánk 1950-ben alapították, és azóta folyamatosan fejlődünk. Kezdetben kis létszámú intézményként működtünk, mára a város egyik legnagyobb és legmodernebb iskolája lettünk.</p>
            </div>

            <div class="info-section">
                <h3>Oktatási programok</h3>
                <ul>
                    <li>Két tanítási nyelvű program (magyar-angol)</li>
                    <li>Informatika specializáció</li>
                    <li>Művészeti tagozat</li>
                    <li>Sportképzés</li>
                </ul>
            </div>

            <div class="info-section">
                <h3>Események</h3>
                <ul>
                    <li>Éves iskolabál</li>
                    <li>Tudományos vetélkedők</li>
                    <li>Sportnap</li>
                    <li>Nyílt napok</li>
                </ul>
            </div>


        </section>
    </main>

    <script>
        document.querySelectorAll('.topnav a').forEach(function(link) {
            link.addEventListener('click', function() {
                document.querySelectorAll('.topnav a').forEach(function(a) {
                    a.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        function myFunction() {
            var x = document.getElementById("myTopnav");
            var links = x.getElementsByTagName("a");
            
            if (x.className.includes("responsive")) {
                x.className = "topnav";
                for (var i = 0; i < links.length; i++) {
                    links[i].style.display = "";
                }
            } else {
                x.className += " responsive";
                for (var i = 0; i < links.length; i++) {
                    links[i].style.display = "block";
                }
            }
        }


    </script>
</body>
</html>
