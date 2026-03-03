<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    hapusJadwal($conn, $id);
}

header('Location: index.php');
exit;
?>