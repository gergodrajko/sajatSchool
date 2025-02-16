<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adatbázis kezelése</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="topnav" id="myTopnav">
        <a class="active" href="display.php">Adatbázis</a>
        <a href="#Contact">Lépj velünk kapcsolatba</a>
        <a href="#About">Egyéb információk</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>

    <div class="content">
        <h2>Adatbázis kezelése</h2>
        <p>Még nincs létrehozva az adatbázisod? Hozz létre most egyet!</p>
        <form method="post">
            <input class="gombinput" type="submit" name="create_database" value="Adatbázis létrehozása">
        </form>
    </div>

    <div class="news-container">
        <h3>Újdonságok</h3>
        <div class="news-marquee">
            <p>🚨 Újdonságok! Ne hagyja ki a legfrissebb adatbázis frissítéseket! 🚨</p>
            <p>🎉 Új funkciók érhetők el az adatbázis kezelésében! 🎉</p>
            <p>🔥 Továbbfejlesztett keresési funkciók! 🔥</p>
            <p>🚀 Folyamatosan bővítjük! 🚀</p>
        </div>
    </div>

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
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }
    </script>
</body>
</html>
