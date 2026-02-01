<?php
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if (isset($_SESSION['kosik'][$id])) {
        // Úplne odstráni produkt z košíka
        unset($_SESSION['kosik'][$id]);
    }
}

header("Location: kosik.php");
exit();