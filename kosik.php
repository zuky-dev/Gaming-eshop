<?php
session_start();
// Zámok: Ak používateľ nie je prihlásený, pošleme ho na login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Košík | Gaming Beast</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body style="background: #0f172a; color: white;">

<header>
    <div class="nav-container">
        <div class="logo"><a href="index.php" style="color:white; text-decoration:none;">GAMING BEAST</a></div>
        <nav>
            <a href="index.php" style="color: #00ff88; text-decoration:none; font-weight:bold;">← Späť k nákupu</a>
        </nav>
    </div>
</header>

<main class="container" style="padding: 50px; max-width: 1200px; margin: 0 auto;">
    <h1 style="border-left: 4px solid #00ff88; padding-left: 15px; text-transform: uppercase;">Tvoj nákupný košík</h1>

    <?php
    if (!empty($_SESSION['kosik'])) {
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>Produkt</th>
                    <th>Množstvo</th>
                    <th>Cena/ks</th>
                    <th>Spolu</th>
                    <th>Akcia</th>
                </tr>
              </thead>
              <tbody>";

        $total = 0;
        foreach ($_SESSION['kosik'] as $id => $qty) {
            $res = $conn->query("SELECT * FROM produkty WHERE id = $id");
            
            if ($p = $res->fetch_assoc()) {
                $subtotal = $p['cena'] * $qty;
                $total += $subtotal;
                
                echo "<tr>";
                echo "<td><strong>" . htmlspecialchars($p['nazov']) . "</strong></td>";
                echo "<td style='text-align:center;'>$qty ks</td>";
                echo "<td style='text-align:center;'>" . number_format($p['cena'], 2, ',', ' ') . " €</td>";
                echo "<td style='color: #00ff88; font-weight: bold; text-align:center;'>" . number_format($subtotal, 2, ',', ' ') . " €</td>";
                echo "<td style='text-align:center;'><a href='odobrat_z_kosika.php?id=$id' class='remove-link'>✖ Odobrať</a></td>";
                echo "</tr>";
            }
        }
        echo "</tbody></table>";

        // Sekcia s celkovou sumou a tlačidlom na objednanie
        echo "<div style='text-align: right; margin-top: 40px;'>
                <h2 style='font-size: 2rem; margin-bottom: 10px;'>Celkom k úhrade: <span style='color: #00ff88;'>" . number_format($total, 2, ',', ' ') . " €</span></h2>
                <p style='color: #94a3b8; margin-bottom: 20px;'>Doprava zdarma na všetky herné produkty!</p>
                <a href='spracovat_objednavku.php' class='btn-hero' style='text-decoration:none; padding: 15px 40px; display: inline-block;'>
                    ZÁVÄZNE OBJEDNAŤ
                </a>
              </div>";
    } else {
        echo "<div style='text-align:center; padding: 100px 0;'>
                <h2 style='color: #94a3b8;'>Tvoj košík zíva prázdnotou.</h2>
                <br>
                <a href='index.php' class='btn-hero' style='text-decoration:none;'>ÍSŤ NAKUPOVAŤ</a>
              </div>";
    }
    ?>
</main>
</body>
</html>