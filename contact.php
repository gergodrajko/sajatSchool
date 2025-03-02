<?php
require_once 'head.php';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kapcsolat</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <meta name="description" content="Kapcsolatfelvétel az iskolával">
</head>

<body>
    <header>
        <nav class="topnav" id="myTopnav">
            <a href="display.php">Adatbázis</a>
            <a href="queries.php">Lekérdezések</a>
            <a class="active" href="contact.php">Lépj velünk kapcsolatba</a>
            <a href="info.php">Egyéb információk</a>
            <a href="admin.php">Admin</a>

            <a href="javascript:void(0);" class="icon" onclick="myFunction()" aria-label="Menü megnyitása">
                <i class="fa fa-bars"></i>
            </a>
        </nav>
    </header>

    <main class="content">
        <section aria-labelledby="contact-title">
            <h2 id="contact-title">Kapcsolat</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fa fa-map-marker"></i>
                    <p>1234 Budapest, Iskola utca 5.</p>
                </div>
                <div class="contact-item">
                    <i class="fa fa-phone"></i>
                    <p>+36 1 234 5678</p>
                </div>
                <div class="contact-item">
                    <i class="fa fa-envelope"></i>
                    <p>info@sajatschool.hu</p>
                </div>
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
            
            if (x.className === "topnav") {
                x.className += " responsive";
                for (var i = 0; i < links.length; i++) {
                    links[i].style.display = "block";
                }
            } else {
                x.className = "topnav";
                for (var i = 0; i < links.length; i++) {

                    links[i].style.display = "";
                }
            }
        }

    </script>
</body>
</html>
