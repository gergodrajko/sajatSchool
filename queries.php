<?php
require_once 'head.php';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lekérdezések</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <meta name="description" content="Adatbázis lekérdezések">
</head>

<body>
    <header>
        <nav class="topnav" id="myTopnav">
            <a href="display.php">Adatbázis</a>
            <a class="active" href="queries.php">Lekérdezések</a>
            <a href="contact.php">Lépj velünk kapcsolatba</a>
            <a href="info.php">Egyéb információk</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()" aria-label="Menü megnyitása">
                <i class="fa fa-bars"></i>
            </a>
        </nav>
    </header>

    <main class="content">
        <section aria-labelledby="queries-title">
            <h2 id="queries-title">Lekérdezések</h2>
            <!-- Content will be added later -->
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
            
            if (x.className === "topnav") {
                x.className += " responsive";
                // Show all menu items
                for (var i = 0; i < links.length; i++) {
                    links[i].style.display = "block";
                }
            } else {
                x.className = "topnav";
                // Reset display for responsive mode
                for (var i = 0; i < links.length; i++) {
                    links[i].style.display = "";
                }
            }
        }

    </script>
</body>
</html>
