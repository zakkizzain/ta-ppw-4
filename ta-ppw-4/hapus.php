<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

$index = $_GET['index'] ?? null;

if ($index !== null && isset($_SESSION['kontak'][$index])) {

    unset($_SESSION['kontak'][$index]);
    

    $_SESSION['kontak'] = array_values($_SESSION['kontak']); 
}


header("Location: kontak.php");
exit();
?>