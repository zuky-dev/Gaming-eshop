<?php
session_start();
// Tu by sa za normálnych okolností uložili dáta do DB tabuľky 'objednavky'
unset($_SESSION['kosik']); // Vyprázdnime košík
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Objednávka odoslaná</title>
</head>
<body style="text-align:center; padding:100px; background:#0f172a; color:white;">
    <h1 style="color:#00ff88;">Misia splnená!</h1>
    <p>Tvoja herná výbava je na ceste. Priprav si miesto na stole.</p>
    <br>
    <a href="index.php" class="btn-hero">Späť na hlavnú stránku</a>
</body>
</html>