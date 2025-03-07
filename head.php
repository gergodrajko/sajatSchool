<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 

$dbname = "SajatIskola";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

$db_selected = $conn->query("SHOW DATABASES LIKE '$dbname'");

if ($db_selected->num_rows == 0) {
    echo "<p style='color:red;'>Az adatbázis nem létezik. Kérlek, kattints az 'Adatbázis létrehozása' gombra!</p>";
} else {
    $conn->select_db($dbname);
}



function createDatabase($conn) {
    global $dbname;

    $conn->query("DROP DATABASE IF EXISTS $dbname");
    $sql = "CREATE DATABASE $dbname CHARACTER SET utf8 COLLATE utf8_hungarian_ci";
    if ($conn->query($sql) === TRUE) {
        $conn->select_db($dbname);
        createStudentsTable($conn);
        createSubjectsTable($conn);
        createClassesTable($conn);
        createMarksTable($conn);
        insertRandomData($conn);
        return "Adatbázis és táblák létrehozva.";
    } else {
        return "Hiba az adatbázis létrehozásakor: " . $conn->error;
    }
}

// Students tábla létrehozása
function createStudentsTable($conn) {
    $sql = "CREATE TABLE students (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        class_id INT(6) UNSIGNED NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Students tábla létrehozva<br>";
    } else {
        echo "Hiba a students tábla létrehozásakor: " . $conn->error;
    }
}

// Subjects tábla létrehozása
function createSubjectsTable($conn) {
    $sql = "CREATE TABLE subjects (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Subjects tábla létrehozva<br>";
    } else {
        echo "Hiba a subjects tábla létrehozásakor: " . $conn->error;
    }
}

// Classes tábla létrehozása
function createClassesTable($conn) {
    $sql = "CREATE TABLE classes (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        year INT(4) NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Classes tábla létrehozva<br>";
    } else {
        echo "Hiba a classes tábla létrehozásakor: " . $conn->error;
    }
}

// Marks tábla létrehozása
function createMarksTable($conn) {
    $sql = "CREATE TABLE marks (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        student_id INT(6) UNSIGNED NOT NULL,
        subject_id INT(6) UNSIGNED NOT NULL,
        mark INT(2) NOT NULL,
        date DATE NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Marks tábla létrehozva<br>";
    } else {
        echo "Hiba a marks tábla létrehozásakor: " . $conn->error;
    }
}

// Véletlenszerű adatok beszúrása
function insertRandomData($conn) {
    // Osztályok beszúrása
    $classes = ['A', 'B'];
    foreach ($classes as $class) {
        for ($i = 9; $i <= 12; $i++) {
            $sql = "INSERT INTO classes (name, year) VALUES ('$class', $i)";
            $conn->query($sql);
        }
    }

    // Tantárgyak beszúrása
    $subjects = ['Matematika', 'Magyar', 'Történelem', 'Fizika', 'Kémia'];
    foreach ($subjects as $subject) {
        $sql = "INSERT INTO subjects (name) VALUES ('$subject')";
        $conn->query($sql);
    }

    // Tanulók beszúrása
    $vezeteknevek = ["Nagy", "Kis", "Szabó", "Kovács", "Tóth", "Horváth", "Varga", "Molnár"];
    $keresztnevek = ["Anna", "Péter", "László", "Katalin", "János", "Éva", "István", "Mária"];
    
    // Create 8 classes (2 per grade from 9th to 12th)
    $classIds = range(1, 8);
    $studentsPerClass = 15; // Pontosan 15 tanuló osztályonként

    foreach ($classIds as $classId) {
        for ($i = 1; $i <= $studentsPerClass; $i++) {
            $vezeteknev = $vezeteknevek[array_rand($vezeteknevek)];
            $keresztnev = $keresztnevek[array_rand($keresztnevek)];
            $name = "$vezeteknev $keresztnev";
            $sql = "INSERT INTO students (name, class_id) VALUES ('$name', $classId)";
            $conn->query($sql);
        }
    }



    // Jegyek beszúrása
    $totalStudents = 120; // 8 classes * 15 students each

    $studentIds = range(1, $totalStudents);

    $subjectIds = range(1, count($subjects));
    foreach ($studentIds as $studentId) {
        foreach ($subjectIds as $subjectId) {
            $markCount = rand(3, 5);
            for ($i = 0; $i < $markCount; $i++) {
                $mark = rand(1, 5);
                $date = date('Y-m-d', strtotime('-' . rand(0, 365) . ' days'));
                $sql = "INSERT INTO marks (student_id, subject_id, mark, date) VALUES ($studentId, $subjectId, $mark, '$date')";
                $conn->query($sql);
            }
        }
    }
}