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
    <style>
        .hidden { display: none; }
        .query-btn { margin: 5px; padding: 10px; cursor: pointer; background-color: #007BFF; color: white; border: none; border-radius: 5px; }
        .query-btn:hover { background-color: #0056b3; }
        .hof-container { padding: 20px; border: 2px solid #ddd; background-color: #f9f9f9; margin-top: 20px; }
        .hof-title { font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 15px; }
        .hof-list { list-style: none; padding: 0; }
        .hof-list li { margin: 5px 0; font-size: 18px; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <meta name="description" content="Adatbázis lekérdezések és Hall of Fame">
</head>

<body>
    <header>
        <nav class="topnav" id="myTopnav">
            <a href="display.php">Adatbázis</a>
            <a class="active" href="queries.php">Lekérdezések</a>
            <a href="contact.php">Lépj velünk kapcsolatba</a>
            <a href="info.php">Egyéb információk</a>
            <a href="admin.php">Admin</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()" aria-label="Menü megnyitása">
                <i class="fa fa-bars"></i>
            </a>
        </nav>
    </header>

    <main class="content">
        <section aria-labelledby="queries-title">
            <h2 id="queries-title">Lekérdezések</h2>

            <button class="query-btn" onclick="toggleVisibility('class-9A')">9A</button>
            <button class="query-btn" onclick="toggleVisibility('class-10A')">10A</button>
            <button class="query-btn" onclick="toggleVisibility('class-11A')">11A</button>
            <button class="query-btn" onclick="toggleVisibility('class-12A')">12A</button>
            <button class="query-btn" onclick="toggleVisibility('class-9B')">9B</button>
            <button class="query-btn" onclick="toggleVisibility('class-10B')">10B</button>
            <button class="query-btn" onclick="toggleVisibility('class-11B')">11B</button>
            <button class="query-btn" onclick="toggleVisibility('class-12B')">12B</button>
            <button class="query-btn" onclick="toggleVisibility('class-averages')">Osztály Átlagok</button>
            <button class="query-btn" onclick="toggleVisibility('subject-overall-averages')">Tanulói Összesített Átlagok</button>
            <button class="query-btn" onclick="toggleVisibility('student-subject-averages')">Tantárgyi Átlagok</button>
            <button class="query-btn" onclick="toggleVisibility('class-subject-averages')">Osztály Tantárgyi Átlagok</button>
            <button class="query-btn" onclick="toggleVisibility('top-10-students')">Legjobb 10 Tanuló</button>
            <button class="query-btn" onclick="toggleVisibility('hall-of-fame')">Hall of Fame</button>

            <div id="query-results">
                <?php
                require_once 'head.php'; // Adatbázis kapcsolat betöltése
                $class_names = [
                    1 => "9A", 2 => "10A", 3 => "11A", 4 => "12A",
                    5 => "9B", 6 => "10B", 7 => "11B", 8 => "12B"
                ];

                foreach ($class_names as $class_id => $class_name) {
                    echo "<div id='class-$class_name' class='hidden'>";
                    $sql = "SELECT name FROM students WHERE class_id = $class_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<h3>$class_name</h3>";
                        while ($row = $result->fetch_assoc()) {
                            echo $row["name"] . "<br>";
                        }
                    } else {
                        echo "<h3>$class_name</h3><p>Nincs adat.</p>";
                    }
                    echo "</div>";
                }

                // Class Averages
                echo "<div id='class-averages' class='hidden'>";
                $sql = "
                    SELECT c.name AS class_name, c.year, AVG(m.mark) AS average_mark
                    FROM marks m
                    JOIN students s ON m.student_id = s.id
                    JOIN classes c ON s.class_id = c.id
                    GROUP BY c.id, c.name, c.year
                    ORDER BY c.year, c.name;
                ";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    echo "<h3>Osztály Átlagok</h3>";
                    while ($row = $result->fetch_assoc()) {
                        echo "Osztály: " . $row["class_name"] . " (" . $row["year"] . ". évfolyam) - Átlagos jegy: " . number_format($row["average_mark"], 2) . "<br>";
                    }
                } else {
                    echo "<p>Nincs adat.</p>";
                }
                echo "</div>";  

                //Subject averages sum
                echo "<div id='subject-overall-averages' class='hidden'>";
                $sql = "SELECT students.name AS student_name, 
                                CONCAT(classes.year, classes.name) AS class_name, 
                                ROUND(AVG(marks.mark), 2) AS overall_average 
                        FROM marks
                        JOIN students ON marks.student_id = students.id
                        JOIN classes ON students.class_id = classes.id
                        GROUP BY students.id, classes.year, classes.name
                        ORDER BY classes.year, classes.name, students.name";
                
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    echo "<h3>Tanulói Összesített Átlagok</h3>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "Tanuló: " . $row["student_name"] . 
                            " | Osztály: " . $row["class_name"] . 
                            " | Összesített Átlag: " . $row["overall_average"] . "<br>";
                    }
                } else {
                    echo "<p>Nincs adat.</p>";
                }
                echo "</div>";
                
                // Subject averages by every subject
                echo "<div id='student-subject-averages' class='hidden'>";
                $sql = "SELECT students.name AS student_name, 
                            CONCAT(classes.year, classes.name) AS class_name, 
                            subjects.name AS subject_name, 
                            ROUND(AVG(marks.mark), 2) AS average_mark 
                        FROM marks
                        JOIN students ON marks.student_id = students.id
                        JOIN subjects ON marks.subject_id = subjects.id
                        JOIN classes ON students.class_id = classes.id
                        GROUP BY students.id, class_name, subjects.id
                        ORDER BY class_name, students.name, subjects.name";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<h3>Tantárgyi Átlagok</h3>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "Tanuló: " . $row["student_name"] . 
                            " | Osztály: " . $row["class_name"] . 
                            " | Tantárgy: " . $row["subject_name"] . 
                            " | Átlag: " . $row["average_mark"] . "<br>";
                    }
                } else {
                    echo "<p>Nincs adat.</p>";
                }
                echo "</div>";

                //Class averages by subjects
                echo "<div id='class-subject-averages' class='hidden'>";
                $sql = "SELECT CONCAT(c.year, c.name) AS class_name, 
                                sub.name AS subject_name, 
                                ROUND(AVG(m.mark), 2) AS average_mark
                        FROM marks m
                        JOIN students s ON m.student_id = s.id
                        JOIN classes c ON s.class_id = c.id
                        JOIN subjects sub ON m.subject_id = sub.id
                        GROUP BY c.id, sub.id
                        ORDER BY c.year, c.name, sub.name";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<h3>Osztályok Tantárgyi Átlaga</h3>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "Osztály: " . $row["class_name"] . 
                            " | Tantárgy: " . $row["subject_name"] . 
                            " | Átlag: " . $row["average_mark"] . "<br>";
                    }
                } else {
                    echo "<p>Nincs adat.</p>";
                }
                echo "</div>";
                // Legjobb 10 tanuló adott évben
                echo "<div id='top-10-students' class='hidden'>";
                
                $selected_year = isset($_GET['year']) ? intval($_GET['year']) : date("Y"); 

                echo "<form method='GET' action='queries.php'>";
                echo "<label for='year-select'>Válassz évfolyamot:</label>";
                echo "<select id='year-select' name='year' onchange='this.form.submit()'>";
                for ($y = date("Y") - 2016; $y <= date("Y")-2013; $y++) {
                    echo "<option value='$y' " . ($y == $selected_year ? "selected" : "") . ">$y</option>";
                }
                echo "</select>";
                echo "</form>";


                //10 legjobb tanuló
                $sql = "SELECT s.name AS student_name, 
                               CONCAT(c.year, c.name) AS class_name, 
                               ROUND(AVG(m.mark), 2) AS average_mark
                        FROM marks m
                        JOIN students s ON m.student_id = s.id
                        JOIN classes c ON s.class_id = c.id
                        WHERE c.year = ?
                        GROUP BY s.id, c.year, c.name
                        ORDER BY average_mark DESC
                        LIMIT 10";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $selected_year);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<h3>Iskola 10 Legjobb Tanulója ($selected_year)</h3>";
                    $rank = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "$rank. " . $row["student_name"] . 
                            " | Osztály: " . $row["class_name"] . 
                            " | Átlag: " . $row["average_mark"] . "<br>";
                        $rank++;
                    }
                } else {
                    echo "<p>Nincs adat.</p>";
                }

                echo "</div>";

                // Hall of Fame
                echo "<div id='hall-of-fame' class='hidden hof-container'>";
                echo "<h2 class='hof-title'>🏆 Hall of Fame 🏆</h2>";

                // Legjobb osztály kiválasztása
                $best_class_sql = "SELECT c.name AS class_name, c.year, ROUND(AVG(m.mark), 2) AS avg_mark
                                   FROM marks m
                                   JOIN students s ON m.student_id = s.id
                                   JOIN classes c ON s.class_id = c.id
                                   GROUP BY c.id, c.name, c.year
                                   ORDER BY avg_mark DESC
                                   LIMIT 1";

                $best_class_result = $conn->query($best_class_sql);

                if ($best_class_result->num_rows > 0) {
                    $best_class = $best_class_result->fetch_assoc();
                    echo "<h3>🏅 Mindenkori Legjobb Osztály: {$best_class['class_name']} ({$best_class['year']})</h3>";
                    echo "<p>Átlagos jegy: {$best_class['avg_mark']}</p>";
                } else {
                    echo "<p>Nincs elérhető adat a legjobb osztályhoz.</p>";
                }

                // 10 Legjobb tanuló
                $top_students_sql = "SELECT s.name AS student_name, CONCAT(c.year, ' ', c.name) AS class_info, 
                                            ROUND(AVG(m.mark), 2) AS average_mark
                                     FROM marks m
                                     JOIN students s ON m.student_id = s.id
                                     JOIN classes c ON s.class_id = c.id
                                     GROUP BY s.id, s.name, c.year, c.name
                                     ORDER BY average_mark DESC
                                     LIMIT 10";

                $top_students_result = $conn->query($top_students_sql);

                if ($top_students_result->num_rows > 0) {
                    echo "<h3>🏆 Top 10 Tanuló (Minden Idők)</h3>";
                    echo "<ul class='hof-list'>";
                    $rank = 1;
                    while ($row = $top_students_result->fetch_assoc()) {
                        echo "<li>🥇 $rank. {$row['student_name']} - {$row['class_info']} (Átlag: {$row['average_mark']})</li>";
                        $rank++;
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Nincs adat a legjobb tanulókról.</p>";
                }

                echo "</div>";

                ?>
            </div>
        </section>
    </main>

    <script>
        function toggleVisibility(id) {
            let elements = document.querySelectorAll('.hidden');
            elements.forEach(el => el.style.display = 'none');

            let targetElement = document.getElementById(id);
            if (targetElement) {
                targetElement.style.display = 'block';
            }
        }

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