<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'];

session_start();

// Vernietig alle sessievariabelen
session_unset();

// Vernietig de sessie
session_destroy();

// Stuur de gebruiker door naar de inlogpagina
header("Location: " . $base_url . "/hiveinsight/login.php");
exit();
?>