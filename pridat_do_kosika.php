<?php
session_start();


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


header("Location: " . $_SERVER['HTTP_REFERER']);

exit();
