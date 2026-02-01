<?php
session_start(); 
session_unset(); 
session_destroy(); 

// Presmerovanie na prihlasovaciu stránku
header("Location: login.php");
exit();

?>
