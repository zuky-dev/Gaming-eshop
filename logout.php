<?php
session_start(); // Musíme spustiť session, aby sme ju mohli zrušiť
session_unset(); // Vymaže všetky premenné v session (košík, meno používateľa...)
session_destroy(); // Úplne zničí session súbor na serveri

// Presmerovanie na prihlasovaciu stránku
header("Location: login.php");
exit();
?>