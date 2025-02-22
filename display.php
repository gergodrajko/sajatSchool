<?php
require_once 'head.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_database'])) {
    $result = createDatabase($conn);
    echo "<div class='alert'>$result</div>";
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adatbázis kezelése</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <meta name="description" content="Hatékony adatbázis kezelő rendszer iskolai használatra">
</head>

<body>
    <header>
        <nav class="topnav" id="myTopnav">
            <a class="active" href="display.php">Adatbázis</a>
            <a href="queries.php">Lekérdezések</a>
            <a href="contact.php">Lépj velünk kapcsolatba</a>


            <a href="info.php">Egyéb információk</a>

            <a href="javascript:void(0);" class="icon" onclick="myFunction()" aria-label="Menü megnyitása">
                <i class="fa fa-bars"></i>
            </a>
        </nav>
    </header>


    <main class="content">
        <section aria-labelledby="database-title">
            <h2 id="database-title">Adatbázis kezelése</h2>
            <p class="lead-text">Még nincs létrehozva az adatbázisod? Hozz létre most egyet!</p>
            <form method="post" class="form-container" onsubmit="return confirm('Biztosan létrehozza az adatbázist?');">

                <button class="gombinput" type="submit" name="create_database">
                    Adatbázis létrehozása
                </button>
            </form>
        </section>
    </main>


    <aside class="news-container" aria-labelledby="news-title">
        <h3 id="news-title">Újdonságok</h3>
        <div class="news-marquee" role="marquee">
            <p>🚨 Újdonságok! Ne hagyja ki a legfrissebb adatbázis frissítéseket! 🚨</p>
            <p>🎉 Új funkciók érhetők el az adatbázis kezelésében! 🎉</p>
            <p>🔥 Továbbfejlesztett keresési funkciók! 🔥</p>
            <p>🚀 Folyamatosan bővítjük! 🚀</p>
        </div>
    </aside>


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
            if (x.classList.contains("responsive")) {
                x.classList.remove("responsive");
            } else {
                x.classList.add("responsive");
            }
        }


    </script>
</body>
</html>
