<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meno = $conn->real_escape_string($_POST['meno']);
    $priezvisko = $conn->real_escape_string($_POST['priezvisko']);
    $prezyvka = $conn->real_escape_string($_POST['prezyvka']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];
    $pass_confirm = $_POST['password_confirm'];
    
    $adresa = $conn->real_escape_string($_POST['adresa']);
    $mesto = $conn->real_escape_string($_POST['mesto']);
    $psc = $conn->real_escape_string($_POST['psc']);
    $krajina = $conn->real_escape_string($_POST['krajina']);
    $telefon = $conn->real_escape_string($_POST['telefon']);

    if ($pass !== $pass_confirm) {
        $error = "Heslá sa nezhodujú!";
    } else {
        $check = $conn->query("SELECT id FROM pouzivatelia WHERE email='$email' OR prezyvka='$prezyvka'");
        if ($check->num_rows > 0) {
            $error = "Email alebo prezývka už existuje!";
        } else {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            // SQL s rozdeleným menom a prezývkou
            $sql = "INSERT INTO pouzivatelia (meno, priezvisko, prezyvka, email, heslo, adresa, mesto, psc, krajina, telefon) 
                    VALUES ('$meno', '$priezvisko', '$prezyvka', '$email', '$hashed_pass', '$adresa', '$mesto', '$psc', '$krajina', '$telefon')";
            
            if ($conn->query($sql)) {
                $success = "Registrácia úspešná! Teraz sa môžeš prihlásiť.";
            } else {
                $error = "Chyba pri registrácii: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Beast | Registrácia</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #000; }

        #video-pozadie {
            position: fixed;
            right: 0; bottom: 0;
            min-width: 100%; min-height: 100%;
            z-index: -1;
            object-fit: cover;
            filter: brightness(25%) contrast(110%);
        }

        .register-box {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(0, 255, 136, 0.3);
            width: 100%;
            max-width: 550px; 
            max-height: 95vh;
            overflow-y: auto;
            z-index: 10;
            color: white;
        }

        h1 { font-family: 'Orbitron', sans-serif; color: #00ff88; text-align: center; margin-bottom: 20px; letter-spacing: 2px; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .full-width { grid-column: span 2; }

        label { font-size: 0.75rem; color: #00ff88; font-family: 'Orbitron', sans-serif; margin-left: 5px; }

        input, select {
            width: 100%;
            padding: 10px 15px;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #334155;
            border-radius: 8px;
            color: white;
            font-size: 0.9rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus { border-color: #00ff88; box-shadow: 0 0 10px rgba(0, 255, 136, 0.2); }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: #00ff88;
            border: none;
            color: #000;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.4s;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .btn-register:hover { background: #fff; box-shadow: 0 0 25px #00ff88; transform: translateY(-2px); }

        .msg { padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.85rem; border: 1px solid; text-align: center; }
        .error { background: rgba(255, 77, 77, 0.2); color: #ff4d4d; border-color: #ff4d4d; }
        .success { background: rgba(0, 255, 136, 0.2); color: #00ff88; border-color: #00ff88; }

        .links { margin-top: 15px; text-align: center; }
        .links a { color: #94a3b8; text-decoration: none; font-size: 0.8rem; }
        .links a:hover { color: #00ff88; }

        .register-box::-webkit-scrollbar { width: 4px; }
        .register-box::-webkit-scrollbar-thumb { background: #00ff88; border-radius: 10px; }
    </style>
</head>
<body>

    <video autoplay muted loop playsinline id="video-pozadie">
        <source src="videos/gpu_close_up.mp4" type="video/mp4">
    </video>

    <div class="register-box">
        <h1>REGISTRÁCIA</h1>

        <?php if($error) echo "<div class='msg error'>$error</div>"; ?>
        <?php if($success) echo "<div class='msg success'>$success</div>"; ?>

        <form action="registracia.php" method="POST">
            <div class="form-grid">
                <div>
                    <label>Meno</label>
                    <input type="text" name="meno" placeholder="Jozef" required>
                </div>
                <div>
                    <label>Priezvisko</label>
                    <input type="text" name="priezvisko" placeholder="Mrkvička" required>
                </div>
                
                <div class="full-width">
                    <label>Prezývka (Nick)</label>
                    <input type="text" name="prezyvka" placeholder="BeastSlayer69" required>
                </div>

                <div class="full-width">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@priklad.com" required>
                </div>
                
                <div>
                    <label>Heslo</label>
                    <input type="password" name="password" placeholder="******" required>
                </div>
                <div>
                    <label>Overiť heslo</label>
                    <input type="password" name="password_confirm" placeholder="******" required>
                </div>

                <div class="full-width">
                    <label>Ulica a číslo</label>
                    <input type="text" name="adresa" placeholder="Hlavná 123" required>
                </div>
                
                <div>
                    <label>Mesto</label>
                    <input type="text" name="mesto" placeholder="Bratislava" required>
                </div>
                <div>
                    <label>PSČ</label>
                    <input type="text" name="psc" placeholder="811 01" required>
                </div>

                <div>
                    <label>Krajina</label>
                    <select name="krajina" required>
                        <option value="Slovensko">Slovensko</option>
                        <option value="Česko">Česko</option>
                    </select>
                </div>
                <div>
                    <label>Telefón</label>
                    <input type="text" name="telefon" placeholder="+421..." required>
                </div>
            </div>

            <button type="submit" class="btn-register">Vytvoriť herný účet</button>
        </form>

        <div class="links">
            <a href="login.php">Už máš účet? Prihlás sa</a>
        </div>
    </div>

</body>
</html>