<?php
session_start();
include 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM produkty WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Produkt nebol nájdený.");
}

$p = $result->fetch_assoc();

$base_path = "images/products/";
$img_path = !empty($p['obrazok']) ? $base_path . $p['obrazok'] : "images/default.jpg";

if (!file_exists($img_path)) {
    $img_path = "images/default.jpg";
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($p['nazov']); ?> | Gaming Beast</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: white; margin: 0; }
        .container { max-width: 1200px; margin: 50px auto; padding: 20px; }
        
        .detail-wrapper { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 50px; 
            background: #1e293b; 
            padding: 40px; 
            border-radius: 20px; 
            border: 1px solid #334155; 
            align-items: start;
        }

        .left-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: sticky;
            top: 20px;
        }

        .product-image-box {
            background: #0f172a;
            border-radius: 15px;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #00ff88;
            min-height: 400px;
        }

        .product-image-box img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }

        h1 { font-family: 'Orbitron'; color: #00ff88; margin-top: 0; font-size: 2.2rem; line-height: 1.2; }
        .cena-velka { font-family: 'Orbitron'; font-size: 2.8rem; color: #00ff88; margin: 20px 0; text-shadow: 0 0 10px rgba(0,255,136,0.3); }
        
        .popis-box { 
            background: rgba(15, 23, 42, 0.5); 
            padding: 25px; 
            border-radius: 12px; 
            border-left: 4px solid #00ff88;
            margin-bottom: 30px;
        }

        .popis-text { line-height: 1.8; color: #cbd5e1; font-size: 1.05rem; }

        /* --- ŠTÝLY PRE SPÄŤ DO OBCHODU --- */
        .btn-back-container {
            margin-top: 0;
            width: 100%;
        }
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: #00ff88;
            background: rgba(0, 255, 136, 0.1);
            padding: 12px 24px;
            border-radius: 8px;
            border: 1px solid #00ff88;
            font-weight: bold;
            font-family: 'Orbitron';
            font-size: 0.9rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
            text-transform: uppercase;
        }
        .btn-back:hover { 
            background: rgba(0, 255, 136, 0.2); 
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.3);
            transform: translateY(-2px);
        }

        /* --- ŠTÝLY PRE TLAČIDLÁ --- */
        .tlacidla-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 10px;
        }

        .btn-add {
            background: #00ff88;
            color: #000;
            padding: 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-family: 'Orbitron';
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
            font-size: 1.1rem;
            text-transform: uppercase;
        }
        .btn-add:hover { 
            background: #00cc6e; 
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.4); 
            transform: translateY(-2px); 
        }

        .btn-cart-view {
            display: block;
            text-align: center;
            text-decoration: none;
            background: transparent;
            color: #fff;
            padding: 15px;
            border: 1px solid #334155;
            border-radius: 8px;
            font-weight: bold;
            font-family: 'Orbitron';
            transition: 0.3s;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .btn-cart-view:hover { 
            border-color: #00ff88; 
            color: #00ff88;
            background: rgba(0, 255, 136, 0.05);
        }

        @media (max-width: 850px) {
            .detail-wrapper { 
                grid-template-columns: 1fr; 
            }
            .left-column { 
                position: static; 
            }
            .product-image-box { 
                min-height: 300px; 
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="detail-wrapper">
        <div class="left-column">
            <div class="product-image-box">
                <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($p['nazov']); ?>">
            </div>
            
            <div class="btn-back-container">
                <a href="index.php" class="btn-back">
                    <span style="font-size: 1.2rem;">←</span>
                    SPÄŤ DO OBCHODU
                </a>
            </div>
        </div>

        <div class="product-info">
            <div style="color: #00ff88; font-weight: bold; margin-bottom: 10px; letter-spacing: 2px;">SKLADOM V GAMING HUB-e</div>
            <h1><?php echo htmlspecialchars($p['nazov']); ?></h1>
            <div class="cena-velka"><?php echo number_format($p['cena'], 2, ',', ' '); ?> €</div>
            
            <div class="popis-box">
                <h3 style="color: #fff; margin-top: 0; font-family: 'Orbitron'; font-size: 0.9rem; letter-spacing: 1px;">TECHNICKÁ ŠPECIFIKÁCIA</h3>
                <div class="popis-text">
                    <?php 
                    if(!empty($p['popis'])) {
                        echo nl2br(htmlspecialchars($p['popis'])); 
                    } else {
                        echo "Detailné informácie o tomto komponente pripravujeme. Pre viac info nás kontaktujte.";
                    }
                    ?>
                </div>
            </div>

            <div style="margin-bottom: 20px; font-size: 0.8rem; color: #64748b;">
                ID PRODUKTU: #BST-<?php echo $id; ?> | ZÁRUKA: 36 MESIACOV
            </div>

            <div class="tlacidla-container">
                <form action="pridat_do_kosika.php" method="POST" style="margin: 0;">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn-add">🛒 PRIDAŤ DO KOŠÍKA</button>
                </form>

                <a href="kosik.php" class="btn-cart-view">VSTÚPIŤ DO KOŠÍKA →</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>