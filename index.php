<?php
require_once 'head.php';
require_once 'display.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_database'])) {
    echo createDatabase($conn);
}

?>