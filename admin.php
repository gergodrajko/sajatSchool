<?php
ob_start();
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
// Ellenőrizzük, hogy az adatbázis kapcsolat létezik
if (!$conn) {
    die("Adatbázis kapcsolat sikertelen: " . mysqli_connect_error());
}

if ($db_selected->num_rows == 0) {
    echo "<p style='color:red;'>Az adatbázis nem létezik. Kérlek, kattints az 'Adatbázis létrehozása' gombra!</p>";
} else {
    $conn->select_db($dbname);
}

// **Új osztály hozzáadása**
if (isset($_POST['add_class'])) {
    $name = trim($_POST['class_name']);
    $year = $_POST['class_year'];
    
    if (!empty($name) && !empty($year)) {
        $stmt = $conn->prepare("INSERT INTO classes (name, year) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $year);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }
}

// **Osztály szerkesztése**
if (isset($_POST['edit_class'])) {
    $id = $_POST['class_id'];
    $name = trim($_POST['class_name']);
    $year = $_POST['class_year'];
    
    if (!empty($id) && !empty($name) && !empty($year)) {
        $stmt = $conn->prepare("UPDATE classes SET name = ?, year = ? WHERE id = ?");
        $stmt->bind_param("sii", $name, $year, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }
}

// **Osztály törlése**
if (isset($_GET['delete_class'])) {
    $id = $_GET['delete_class'];
    
    $stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}

// **Osztályok lekérdezése**
$classes = $conn->query("SELECT * FROM classes");

if (isset($_POST['add_mark'])) {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $mark = $_POST['mark'];
    $date = $_POST['date'];
    
    if (!empty($student_id) && !empty($subject_id) && !empty($mark) && !empty($date)) {
        $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, mark, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $student_id, $subject_id, $mark, $date);
        $stmt->execute();
        $stmt->close();

        header("Location: admin.php");
        exit();
    }
}

// **Jegy szerkesztése**
if (isset($_POST['edit_mark'])) {
    $id = $_POST['mark_id'];
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $mark = $_POST['mark'];
    $date = $_POST['date'];
    
    if (!empty($id) && !empty($student_id) && !empty($subject_id) && !empty($mark) && !empty($date)) {
        $stmt = $conn->prepare("UPDATE marks SET student_id = ?, subject_id = ?, mark = ?, date = ? WHERE id = ?");
        $stmt->bind_param("iiisi", $student_id, $subject_id, $mark, $date, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }
}

// **Jegy törlése**
if (isset($_GET['delete_mark'])) {
    $id = $_GET['delete_mark'];
    
    $stmt = $conn->prepare("DELETE FROM marks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $conn->query("SET @new_id = 0;");
    $conn->query("UPDATE marks SET id = (@new_id := @new_id + 1) ORDER BY id;");
    $conn->query("ALTER TABLE marks AUTO_INCREMENT = 1;");

    header("Location: admin.php");
    exit();
}

// **Jegyek lekérdezése**
$marks = $conn->query("SELECT * FROM marks");

// **Új tantárgy hozzáadása**
if (isset($_POST['add_subject'])) {
    $name = trim($_POST['subject_name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO subjects (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }
}

// **Tantárgy szerkesztése**
if (isset($_POST['edit_subject'])) {
    $id = $_POST['subject_id'];
    $name = trim($_POST['subject_name']);
    if (!empty($id) && !empty($name)) {
        $stmt = $conn->prepare("UPDATE subjects SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }
}

// **Tantárgy törlése (csak ha nincs hozzá jegy)**
if (isset($_GET['delete_subject'])) {
    $id = $_GET['delete_subject'];

    // Ellenőrizzük, hogy van-e jegy az adott tantárgyból
    $check = $conn->prepare("SELECT COUNT(*) FROM marks WHERE subject_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count == 0) {
        $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $conn->query("SET @new_id = 0;");
        $conn->query("UPDATE subjects SET id = (@new_id := @new_id + 1) ORDER BY id;");
        $conn->query("ALTER TABLE subjects AUTO_INCREMENT = 1;");
    } else {
        echo "<script>alert('Nem törölhető! Először töröld a hozzá tartozó jegyeket.');</script>";
    }

    header("Location: admin.php");
    exit();
}

// **Tantárgyak lekérdezése**
$subjects = $conn->query("SELECT * FROM subjects");

// **Új tanuló hozzáadása**
if (isset($_POST['add_student'])) {
    $name = trim($_POST['student_name']);
    $class_id = $_POST['class_id'];
    
    if (!empty($name) && !empty($class_id)) {
        $stmt = $conn->prepare("INSERT INTO students (name, class_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $class_id);
        $stmt->execute();
        $stmt->close();

        header("Location: admin.php");
        exit();
    }
}

// **Tanuló szerkesztése**
if (isset($_POST['edit_student'])) {
    $id = $_POST['student_id'];
    $name = trim($_POST['student_name']);
    $class_id = $_POST['class_id'];

    if (!empty($id) && !empty($name) && !empty($class_id)) {
        $stmt = $conn->prepare("UPDATE students SET name = ?, class_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $name, $class_id, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }
}

// **Tanuló törlése és ID újrasorszámozása**
if (isset($_GET['delete_student'])) {
    $id = $_GET['delete_student'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $conn->query("SET @new_id = 0;");
    $conn->query("UPDATE students SET id = (@new_id := @new_id + 1) ORDER BY id;");
    $conn->query("ALTER TABLE students AUTO_INCREMENT = 1;");

    header("Location: admin.php");
    exit();
}

// **Tanulók lekérdezése**
$students = $conn->query("SELECT * FROM students");

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Kezelő</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <meta name="description" content="Admin oldal">
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 60%; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-top: 20px; }
        button { padding: 5px 10px; margin: 5px; }
    </style>
</head>
<body>
    <header>
        <nav class="topnav" id="myTopnav">
                <a href="display.php">Adatbázis</a>
                <a href="queries.php">Lekérdezések</a>
                <a href="contact.php">Lépj velünk kapcsolatba</a>
                <a href="info.php">Egyéb információk</a>
                <a class="active" href="admin.php">Admin</a>
                
                <a href="javascript:void(0);" class="icon" onclick="myFunction()" aria-label="Menü megnyitása">

                    <i class="fa fa-bars"></i>
                </a>
            </nav>
    </header>

    <h1>Admin Kezelő</h1>
    <button onclick="document.getElementById('subjects').scrollIntoView({ behavior: 'smooth' });">
    Tantárgyak kezelése
    </button>
    <button onclick="document.getElementById('students').scrollIntoView({ behavior: 'smooth' });">
    Diákok kezelése
    </button>
    <button onclick="document.getElementById('marks').scrollIntoView({ behavior: 'smooth' });">
    Osztályzatok kezelése
    </button>
    <button onclick="document.getElementById('classes').scrollIntoView({ behavior: 'smooth' });">
    Osztályok kezelése
    </button>
    <!-- **TANTÁRGYAK KEZELÉSE** -->
    <h2 id="subjects">Tantárgyak kezelése</h2>
    <form method="POST">
        <input type="text" name="subject_name" placeholder="Új tantárgy neve" required>
        <button type="submit" name="add_subject">Mentés</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Név</th>
            <th>Műveletek</th>
        </tr>
        <?php while ($row = $subjects->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="subject_id" value="<?= $row['id'] ?>">
                        <input type="text" name="subject_name" value="<?= htmlspecialchars($row['name']) ?>" required>
                        <button type="submit" name="edit_subject">Módosít</button>
                    </form>
                    <a href="admin.php?delete_subject=<?= $row['id'] ?>" onclick="return confirm('Biztosan törölni szeretnéd?');">
                        <button style="background-color:red; color:white;">Törlés</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- **TANULÓK KEZELÉSE** -->
    <h2 id="students">Tanulók kezelése</h2>
    <form method="POST">
        <input type="text" name="student_name" placeholder="Tanuló neve" required>
        <input type="number" name="class_id" placeholder="Osztály ID" required>
        <button type="submit" name="add_student">Mentés</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Név</th>
            <th>Osztály ID</th>
            <th>Műveletek</th>
        </tr>
        <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['class_id']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                        <input type="text" name="student_name" value="<?= htmlspecialchars($row['name']) ?>" required>
                        <input type="number" name="class_id" value="<?= htmlspecialchars($row['class_id']) ?>" required>
                        <button type="submit" name="edit_student">Módosít</button>
                    </form>
                    <a href="admin.php?delete_student=<?= $row['id'] ?>" onclick="return confirm('Biztosan törölni szeretnéd?');">
                        <button style="background-color:red; color:white;">Törlés</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2 id="marks">Jegyek kezelése</h2>

    <!-- **ÚJ jegy hozzáadása** -->
    <form method="POST">
        <input type="number" name="student_id" placeholder="Tanuló ID" required>
        <input type="number" name="subject_id" placeholder="Tantárgy ID" required>
        <input type="number" name="mark" placeholder="Jegy" required>
        <input type="date" name="date" required>
        <button type="submit" name="add_mark">Mentés</button>
    </form>

    <h2>Jegyek listája</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tanuló ID</th>
            <th>Tantárgy ID</th>
            <th>Jegy</th>
            <th>Dátum</th>
            <th>Műveletek</th>
        </tr>
        <?php while ($row = $marks->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['subject_id']) ?></td>
                <td><?= htmlspecialchars($row['mark']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td>
                    <!-- MÓDOSÍTÁS FORM -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="mark_id" value="<?= $row['id'] ?>">
                        <input type="number" name="student_id" value="<?= htmlspecialchars($row['student_id']) ?>" required>
                        <input type="number" name="subject_id" value="<?= htmlspecialchars($row['subject_id']) ?>" required>
                        <input type="number" name="mark" value="<?= htmlspecialchars($row['mark']) ?>" required>
                        <input type="date" name="date" value="<?= htmlspecialchars($row['date']) ?>" required>
                        <button type="submit" name="edit_mark">Módosít</button>
                    </form>

                    <!-- TÖRLÉS -->
                    <a href="admin.php?delete_mark=<?= $row['id'] ?>" onclick="return confirm('Biztosan törölni szeretnéd?');">
                        <button style="background-color:red; color:white;">Törlés</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2 id="classes">Osztályok kezelése</h2>

    <form method="POST">
        <input type="text" name="class_name" placeholder="Osztály neve" required>
        <input type="number" name="class_year" placeholder="Évfolyam" required>
        <button type="submit" name="add_class">Mentés</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Név</th>
            <th>Évfolyam</th>
            <th>Műveletek</th>
        </tr>
        <?php while ($row = $classes->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['year']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="class_id" value="<?= $row['id'] ?>">
                        <input type="text" name="class_name" value="<?= htmlspecialchars($row['name']) ?>" required>
                        <input type="number" name="class_year" value="<?= htmlspecialchars($row['year']) ?>" required>
                        <button type="submit" name="edit_class">Módosít</button>
                    </form>
                    <a href="admin.php?delete_class=<?= $row['id'] ?>" onclick="return confirm('Biztosan törölni szeretnéd?');">
                        <button style="background-color:red; color:white;">Törlés</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

<script>
    
</script>
</html>

<?php mysqli_close($conn); ob_end_flush(); ?>
