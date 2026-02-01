<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// --- LOGIKA PRE DYNAMICKÉ POZADIE ---
$bg_video = "default_gaming.mp4";
$bg_title = "VITAJTE V NAŠOM HERNOM ESHOPE";
$bg_text = "Vyber si tie najlepšie komponenty pre tvoj stroj.";
$use_image = false;
$custom_bg_image = "";

// Kľúčová zmena: PHP skontroluje, čo je v URL adrese
if(isset($_GET['kat']) && $_GET['kat'] == 'vypredaj') {
    $use_image = true;
    $custom_bg_image = "images/vypredaj.jpg"; // Pridaný custom obrázok pre výpredaj
    $bg_title = "VÝPREDAJ";
    $bg_text = "Brutálne zľavy na vybrané kúsky. Nečakaj, kým zmiznú!";
} 
// Ostatné kategórie (procesory, grafiky atď.)
elseif(isset($_GET['kat']) && is_numeric($_GET['kat'])) {
    switch($_GET['kat']) {
        case 4: $bg_video = "cpu_video.mp4"; $bg_title = "PROCESORY"; $bg_text = "Srdce tvojho počítača. Výkon bez kompromisov."; break;
        case 2: $bg_video = "gpu_video.mp4"; $bg_title = "GRAFICKÉ KARTY"; $bg_text = "Uži si každý detail v 4K rozlíšení."; break;
        case 1: $bg_video = "pc_video.mp4"; $bg_title = "HERNÉ ZOSTAVY"; $bg_text = "Hotové stroje vyladené našimi expertmi."; break;
        case 3: $bg_video = "monitor_video.mp4"; $bg_title = "MONITORY"; $bg_text = "Pohľad, ktorý ťa vtiahne priamo do hry."; break;
        case 6: $bg_video = "periferie_video.mp4"; $bg_title = "PRÍSLUŠENSTVO"; $bg_text = "Tvoje zbrane v boji o víťazstvo."; break;
    }
} else {
    $use_image = true; // Sme na hlavnej stránke (bez kategórie)
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herný Obchod | Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        /* Základné štýly */
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: white; margin: 0; overflow-x: hidden; }
        .logo a, .category-btn, .section-title, h1, h3 { font-family: 'Orbitron', sans-serif; }
        
        .hero-section { 
            position: relative; 
            height: 450px; 
            overflow: hidden; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            text-align: center;
            border-bottom: 2px solid #00ff88;
        }

        /* --- ŠTÝLY PRE REKLAMY --- */
        .main-layout-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            max-width: 1700px; /* Široký kontajner aby vošli aj reklamy */
            margin: 0 auto;
            padding: 40px 10px;
        }

        .promo-sidebar {
            width: 200px;
            min-width: 200px;
            height: 600px;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid #00ff88;
            border-radius: 12px;
            position: sticky;
            top: 100px; /* Zostane visieť pri skrolovaní */
            padding: 15px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .promo-item { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        /* Štýly pre výpredaj */
        .vypredaj-stiker {
            background: linear-gradient(45deg, #ff0000, #ff4d4d);
            color: white;
            font-size: 0.65rem;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            border: 1px solid #ff0000;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 77, 77, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 77, 77, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 77, 77, 0); }
        }

        /* Skryť reklamy na mobiloch */
        @media (max-width: 1400px) {
            .promo-sidebar { display: none; }
        }

        /* Tvoj kód mriežky */
        .produktova-mriezka { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; margin-top: 20px; }
        .karta-produktu { background: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; }
        .karta-produktu:hover { transform: translateY(-10px); border-color: #00ff88; box-shadow: 0 10px 30px rgba(0, 255, 136, 0.2); }
        .obsah-karty { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .btn-detail { display: block; text-align: center; text-decoration: none; border: 1px solid #00ff88; color: #00ff88; padding: 12px; font-weight: bold; border-radius: 6px; transition: 0.3s; font-size: 0.8rem; }
        .btn-quick-add { width: 100%; background: #00ff88; color: #000; border: none; padding: 12px; border-radius: 6px; font-family: 'Orbitron'; font-weight: bold; font-size: 0.8rem; cursor: pointer; transition: 0.3s; text-transform: uppercase; }
        .cena { font-family: 'Orbitron', sans-serif; letter-spacing: 1px; color: #00ff88; }
    </style>
</head>
<body>

<div id="menu-overlay" class="overlay"></div>
<nav id="side-menu" class="side-nav">
    <div class="side-nav-header">
        <h3>PONUKA</h3>
        <button id="close-btn">×</button>
    </div>
    <a href="index.php">🔥 Odporúčané</a>
    <hr style="border: 0; border-top: 1px solid #334155; margin: 10px 20px;">
    <?php
    $res_kat = $conn->query("SELECT * FROM kategorie ORDER BY nazov ASC");
    while($kat = $res_kat->fetch_assoc()) {
        $cisty_nazov = preg_replace('/^[0-9]+\.\s+/', '', $kat['nazov']);
        // Preskočíme výpredaj, aby sa nezobrazil medzi kategóriami
        if(strpos(strtolower($cisty_nazov), 'výpredaj') === false) {
            echo "<a href='index.php?kat=" . $kat['id'] . "'>" . htmlspecialchars($cisty_nazov) . "</a>";
        }
    }
    ?>
    <!-- PRIDANÝ ODPOČINOK A VÝPREDAJ DO BOČNÉHO MENU - TEN SOM PRIDAL JA -->
    <hr style="border: 0; border-top: 1px solid #334155; margin: 10px 20px;">
    <a href="index.php?kat=vypredaj" style="color: #ff4d4d; font-weight: bold;">🔥 Výpredaj</a>
</nav>

<header style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #1e293b; position: sticky; top: 0; z-index: 1000;">
    <div class="nav-container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 15px 20px;">
        <div class="logo">
            <a href="index.php" style="color:#00ff88; text-decoration:none; font-weight: bold; font-size: 1.6rem; letter-spacing: 3px;">HERNÝ<span style="color:#fff;">ESHOP</span></a>
        </div>

        <div class="search-wrapper" style="flex-grow: 1; max-width: 450px; margin: 0 30px; position: relative;">
            <form action="index.php" method="GET" style="margin: 0;">
                <input type="text" name="search" placeholder="Hľadať komponenty..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       style="width: 100%; background: #1e293b; border: 1px solid #334155; padding: 10px 15px 10px 40px; border-radius: 20px; color: white; outline: none; transition: 0.3s;">
                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;">🔍</span>
            </form>
        </div>

        <div class="nav-links" style="display: flex; align-items: center; gap: 20px;">
            <div class="user-info" style="text-align: right; border-right: 1px solid #334155; padding-right: 15px;">
                <div style="color: #94a3b8; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px;">Prihlásený</div>
                <strong style="color: #00ff88; font-size: 0.9rem;"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Bojovník'; ?></strong>
            </div>
            <a href="kosik.php" class="cart-link" style="color: #fff; text-decoration: none; background: #1e293b; padding: 10px 15px; border-radius: 8px; border: 1px solid #334155;">
                🛒 <span style="color: #00ff88; font-weight: bold;"><?php echo isset($_SESSION['kosik']) ? array_sum($_SESSION['kosik']) : '0'; ?></span>
            </a>
            <a href="logout.php" title="Odhlásiť sa" style="color: #ff4d4d; font-size: 1.2rem; text-decoration: none;">✕</a>
        </div>
    </div>

    <div class="sub-nav" style="background: rgba(30, 41, 59, 0.5); border-top: 1px solid rgba(255,255,255,0.05);">
        <div style="max-width: 1200px; margin: 0 auto; padding: 10px 20px; display: flex; align-items: center; gap: 30px;">
            <button id="menu-btn" class="category-btn" style="background: transparent; color: #00ff88; border: 1px solid #00ff88; padding: 5px 15px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">☰ KATEGÓRIE</button>
            <nav class="quick-links" style="display: flex; gap: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                <a href="index.php" style="color: #94a3b8; text-decoration: none;">Novinky</a>
                <a href="index.php?kat=1" style="color: #94a3b8; text-decoration: none;">Zostavy</a>
                <a href="index.php?kat=2" style="color: #94a3b8; text-decoration: none;">Grafiky</a>
                <!-- Výpredaj s ohnikom v hornej navigácii -->
                <a href="index.php?kat=vypredaj" style="color: #ff4d4d; text-decoration: none;">🔥 Výpredaj</a>
            </nav>
        </div>
    </div>
</header>

<section class="hero-section" style="
    <?php 
    if ($use_image && $custom_bg_image != "") {
        // Ak máme custom obrázok (pre výpredaj)
        echo "background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.7)), url('$custom_bg_image') no-repeat center center; background-size: cover;";
    } elseif ($use_image) {
        // Ak máme použiť obrázok, ale nie custom (hlavná stránka)
        echo "background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.7)), url('images/hero-bg.jpg') no-repeat center center; background-size: cover;";
    } else {
        // Inak použijeme video
        echo "background: #000;";
    }
    ?>">
    
    <?php if (!$use_image): ?>
        <video autoplay muted loop playsinline id="hero-video" 
               style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(40%) contrast(110%);">
            <source src="videos/<?php echo $bg_video; ?>" type="video/mp4">
        </video>
    <?php endif; ?>
    
    <div class="hero-content" style="position: relative; z-index: 2; padding: 0 20px;">
        <h1 style="font-size: 3.5rem; color: #00ff88; margin-bottom: 10px; text-shadow: 0 0 20px rgba(0,255,136,0.5); letter-spacing: 2px;">
            <?php echo $bg_title; ?>
        </h1>
        <p style="font-size: 1.2rem; color: #fff; max-width: 600px; margin: 0 auto; opacity: 0.9;">
            <?php echo $bg_text; ?>
        </p>
    </div>
    <div style="position: absolute; bottom: 0; width: 100%; height: 4px; 
                background: <?php echo (isset($_GET['kat']) && $_GET['kat'] == 'vypredaj') ? 'linear-gradient(90deg, transparent, #ff4d4d, transparent)' : 'linear-gradient(90deg, transparent, #00ff88, transparent)'; ?>; 
                z-index: 3; 
                box-shadow: 0 0 15px <?php echo (isset($_GET['kat']) && $_GET['kat'] == 'vypredaj') ? '#ff4d4d' : '#00ff88'; ?>;">
    </div>
</section>

<div class="main-layout-container">

    <aside class="promo-sidebar" id="promo-left">
        <div class="promo-content"></div>
    </aside>

    <main id="produkty" style="flex: 1; max-width: 1200px;">
        <?php
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $s = $conn->real_escape_string($_GET['search']);
            echo "<h2 class='section-title' style='color: #fff; border-left: 5px solid #00ff88; padding-left: 20px; margin-bottom: 40px;'>VÝSLEDKY PRE: \"$s\"</h2>";
            $res = $conn->query("SELECT * FROM produkty WHERE nazov LIKE '%$s%' ORDER BY cena DESC");
        } 
        elseif(!isset($_GET['kat']) || empty($_GET['kat'])) {
            echo "<h2 class='section-title' style='color: #fff; border-left: 5px solid #00ff88; padding-left: 20px; margin-bottom: 40px;'>NOVINKY</h2>";
            $res = $conn->query("SELECT * FROM produkty WHERE id IN (10, 49, 48) ORDER BY FIELD(id, 10, 49, 48)");
        } 
        elseif($_GET['kat'] == 'vypredaj') {
            echo "<h2 class='section-title' style='color: #fff; border-left: 5px solid #ff4d4d; padding-left: 20px; margin-bottom: 40px;'>VÝPREDAJ</h2>";
            // Opravený dotaz: Zobrazíme 3 produkty (ako na hlavnej stránke) - môžete zmeniť podľa potreby
            $res = $conn->query("SELECT * FROM produkty WHERE id IN (102, 101, 100) ORDER BY FIELD(id, 102, 101, 100)");
        }
        else {
            $kat_id = intval($_GET['kat']); 
            $kat_name_res = $conn->query("SELECT nazov FROM kategorie WHERE id = $kat_id");
            $kat_name_data = $kat_name_res->fetch_assoc();
            $nazov_kat = preg_replace('/^[0-9]+\.\s+/', '', $kat_name_data['nazov'] ?? 'Produkty');
            echo "<h2 class='section-title' style='color: #fff; border-left: 5px solid #00ff88; padding-left: 20px; margin-bottom: 40px; text-transform: uppercase;'>" . htmlspecialchars($nazov_kat) . "</h2>";
            $res = ($kat_id == 2 || $kat_id == 4) ? $conn->query("SELECT * FROM produkty WHERE kategoria_id = $kat_id ORDER BY id DESC") : $conn->query("SELECT * FROM produkty WHERE kategoria_id = $kat_id ORDER BY id ASC");
        }

        ?>

        <div class="produktova-mriezka">
            <?php
            if ($res && $res->num_rows > 0) {
                while($p = $res->fetch_assoc()) {
                    $base_path = "images/products/";
                    $obrazok = (!empty($p['obrazok']) && file_exists($base_path . $p['obrazok'])) ? $base_path . $p['obrazok'] : "images/default.jpg";
                    ?>
                    <div class="karta-produktu">
                        <div style="background: #0f172a; padding: 20px; display: flex; align-items: center; justify-content: center;">
                            <img src="<?php echo $obrazok; ?>" alt="Produkt" style="width: 100%; height: 180px; object-fit: contain;">
                        </div>
                        <div class="obsah-karty">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <!-- Pridáme štítok VÝPREDAJ iba pre stránku výpredaja -->
                                    <?php if(isset($_GET['kat']) && $_GET['kat'] == 'vypredaj'): ?>
                                        <span class="vypredaj-stiker">VÝPREDAJ</span>
                                    <?php else: ?>
                                        <span style="background: rgba(0, 255, 136, 0.1); color: #00ff88; font-size: 0.65rem; padding: 4px 8px; border-radius: 4px; font-weight: bold; border: 1px solid #00ff88;">SKLADOM</span>
                                    <?php endif; ?>
                                    <small style="color: #64748b;">ID: #<?php echo $p['id']; ?></small>
                                </div>
                                <h3 style="margin: 15px 0; font-size: 1.05rem; color: #fff; line-height: 1.4;"><?php echo htmlspecialchars($p['nazov']); ?></h3>
                            </div>
                            <div>
                                <div class="cena" style="font-size: 1.6rem; font-weight: bold; margin-bottom: 15px;">
                                    <?php echo number_format($p['cena'], 2, ',', ' '); ?> €
                                </div>
                                <div class="akcie-tlacidla">
                                    <a href="detail.php?id=<?php echo $p['id']; ?>" class="btn-detail">ZOBRAZIŤ DETAIL</a>
                                    <form action="pridat_do_kosika.php" method="POST" style="margin: 0;">
                                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" class="btn-quick-add">DO KOŠÍKA</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                echo "<p style='color: #94a3b8; text-align: center; padding: 40px;'>Žiadne produkty na zobrazenie.</p>";
            } ?>
        </div>
    </main>

    <aside class="promo-sidebar" id="promo-right">
        <div class="promo-content"></div>
    </aside>

</div> <footer style="background: #0f172a; border-top: 1px solid #1e293b; padding: 40px 20px; text-align: center;">
    <p style="color: #64748b; font-size: 0.9rem;">&copy; <?php echo date("Y"); ?> Herný obchod</p>
</footer>

<script>
    // Menu logika
    const btn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const menu = document.getElementById('side-menu');
    const overlay = document.getElementById('menu-overlay');
    function toggleMenu() { menu.classList.toggle('active'); overlay.classList.toggle('active'); }
    btn.onclick = toggleMenu;
    closeBtn.onclick = toggleMenu;
    overlay.onclick = toggleMenu;

    // Reklamy logika
    const availableProducts = [
        <?php 
        if ($res && $res->num_rows > 0) {
            $res->data_seek(0);
            while($promo = $res->fetch_assoc()) {
                $p_img = (!empty($promo['obrazok'])) ? "images/products/" . $promo['obrazok'] : "images/default.jpg";
                echo "{ id: '".$promo['id']."', nazov: '".addslashes($promo['nazov'])."', cena: '".$promo['cena']."', img: '".$p_img."' },";
            }
        }
        ?>
    ];

    function updatePromo(elementId) {
        if (availableProducts.length === 0) return;
        const container = document.getElementById(elementId).querySelector('.promo-content');
        const randomP = availableProducts[Math.floor(Math.random() * availableProducts.length)];
        container.innerHTML = `
            <div class="promo-item">
                <div style="color: #00ff88; font-family: 'Orbitron'; font-size: 0.7rem; margin-bottom: 10px;">ODPORÚČAME</div>
                <img src="${randomP.img}" style="width: 100%; height: 120px; object-fit: contain; margin-bottom: 10px; filter: drop-shadow(0 0 5px rgba(0,255,136,0.3));">
                <h4 style="font-size: 0.8rem; color: #fff; margin-bottom: 10px; font-family: 'Orbitron'; height: 40px; overflow: hidden;">${randomP.nazov}</h4>
                <div style="color: #00ff88; font-size: 1.1rem; font-weight: bold; margin-bottom: 15px;">${parseFloat(randomP.cena).toLocaleString('sk-SK')} €</div>
                <a href="detail.php?id=${randomP.id}" style="display: block; border: 1px solid #00ff88; color: #00ff88; padding: 8px; text-decoration: none; border-radius: 4px; font-size: 0.7rem;">DETAIL</a>
            </div>
        `;
    }

    setInterval(() => updatePromo('promo-left'), 3000);
    setInterval(() => updatePromo('promo-right'), 3000);
    updatePromo('promo-left');
    updatePromo('promo-right');
</script>
</body>
</html>