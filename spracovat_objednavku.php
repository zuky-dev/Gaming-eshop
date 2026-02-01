<?php
session_start();
include 'db.php';

// Kontrola prihlásenia a košíka
if (!isset($_SESSION['user_id']) || empty($_SESSION['kosik'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$celkova_suma = 0;

// 1. Výpočet sumy
foreach ($_SESSION['kosik'] as $id => $qty) {
    $res = $conn->query("SELECT cena FROM produkty WHERE id = $id");
    if ($p = $res->fetch_assoc()) {
        $celkova_suma += ($p['cena'] * $qty);
    }
}

// 2. Vloženie hlavnej objednávky
$sql_obj = "INSERT INTO objednavky (pouzivatel_id, celkova_cena, status) 
            VALUES ($user_id, $celkova_suma, 'Nová')";

if ($conn->query($sql_obj)) {
    // ZÍSKANIE ČÍSLA OBJEDNÁVKY Z DATABÁZY
    $objednavka_id = $conn->insert_id;

    // ULOŽENIE ČÍSLA DO SESSION PRE SÚBOR HOTOVO.PHP
    $_SESSION['last_order_id'] = $objednavka_id;

    // 3. Vloženie jednotlivých položiek
    foreach ($_SESSION['kosik'] as $id => $qty) {
        $res = $conn->query("SELECT cena FROM produkty WHERE id = $id");
        if ($p = $res->fetch_assoc()) {
            $cena_ks = $p['cena'];
            $conn->query("INSERT INTO polozky_objednavky (objednavka_id, produkt_id, pocet, cena_za_kus) 
                          VALUES ($objednavka_id, $id, $qty, $cena_ks)");
        }
    }

    // 4. Vyprázdnenie košíka
    unset($_SESSION['kosik']);
    
    // Presmerovanie na poďakovanie
    header("Location: hotovo.php");
    exit(); // Vždy pridaj exit po header redirecte
} else {
    die("Chyba pri objednávke: " . $conn->error);
}
?>