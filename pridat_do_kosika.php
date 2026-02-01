<?php
session_start();

// Zmenené na $_POST, pretože tak to posielaš z detailu
if (isset($_POST['product_id'])) {
    $id = intval($_POST['product_id']);
    
    if (!isset($_SESSION['kosik'])) {
        $_SESSION['kosik'] = [];
    }

    if (isset($_SESSION['kosik'][$id])) {
        $_SESSION['kosik'][$id]++;
    } else {
        $_SESSION['kosik'][$id] = 1;
    }
}

// Presmerovanie do košíka, aby používateľ videl, že sa to podarilo
// Namiesto fixného Location: kosik.php dáme:
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();