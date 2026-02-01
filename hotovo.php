<?php
session_start();
// Získame ID zo session, ak neexistuje, dáme tam nulu alebo text
$order_number = isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : 'N/A';

// Voliteľné: Po zobrazení môžeme ID zmazať, aby sa pri refreshnutí stránky nezobrazovalo znova
// unset($_SESSION['last_order_id']);
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Objednávka prijatá | Gaming Beast</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .success-container {
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: #0f172a;
            color: white;
        }
        .success-icon {
            font-size: 5rem;
            color: #00ff88;
            margin-bottom: 20px;
        }
        .order-number {
            color: #00ff88;
            font-weight: bold;
            font-size: 1.5rem;
            border: 1px dashed #00ff88;
            padding: 10px 20px;
            margin: 20px 0;
            display: inline-block;
        }
        h1 { font-size: 3rem; margin-bottom: 10px; }
        p { color: #94a3b8; font-size: 1.2rem; margin-bottom: 30px; }
    </style>
</head>
<body>

<div class="success-container">
    <div class="success-icon">✔</div>
    <h1>ĎAKUJEME ZA VAŠU OBJEDNÁVKU!</h1>
    
    <div class="order-number">ČÍSLO OBJEDNÁVKY: #<?php echo $order_number; ?></div>

    <h2>Tovar posielame na dobierku prostredníctvom DHL.</h2>
    <p>Vaša objednávka sa už pripravuje. Potvrdenie sme odoslali na váš e-mail.</p>
    <a href="index.php" class="btn-hero" style="text-decoration:none; padding: 15px 40px;">SPÄŤ DO OBCHODU</a>
</div>

</body>
</html>