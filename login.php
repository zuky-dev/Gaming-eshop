<?php
session_start();
include 'db.php';

// Ak je už používateľ prihlásený, presmeruj ho do obchodu
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM pouzivatelia WHERE email='$email'");
    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($pass, $user['heslo'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['meno'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Nesprávne heslo!";
        }
    } else {
        $error = "Používateľ neexistuje!";
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Beast | Vstup do systému</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
            background: #000; 
        }

        #video-pozadie {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
            filter: brightness(35%) contrast(110%);
        }

        .login-box {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(15px);
            padding: 50px 40px;
            border-radius: 20px;
            border: 1px solid rgba(0, 255, 136, 0.3);
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);
            width: 100%;
            max-width: 420px;
            text-align: center;
            z-index: 10;
        }

        h1 { 
            font-family: 'Orbitron', sans-serif; 
            color: #00ff88; 
            margin-bottom: 30px; 
            letter-spacing: 3px;
            text-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
        }

        input {
            width: 100%;
            padding: 14px 20px;
            margin-bottom: 20px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid #334155;
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #00ff88;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.2);
        }

        .btn-vstup {
            width: 100%;
            padding: 15px;
            background: #00ff88;
            border: none;
            color: #000;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.4s;
            text-transform: uppercase;
        }

        .btn-vstup:hover {
            background: #fff;
            box-shadow: 0 0 25px #00ff88;
            transform: translateY(-2px);
        }

        .error-msg {
            background: rgba(255, 77, 77, 0.2);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #ff4d4d;
            font-size: 0.9rem;
        }

        .links { margin-top: 25px; }
        .links a { color: #94a3b8; text-decoration: none; font-size: 0.85rem; transition: 0.3s; }
        .links a:hover { color: #00ff88; }
    </style>
</head>
<body>

    <video autoplay muted loop playsinline id="video-pozadie">
        <source src="videos/gpu_close_up.mp4" type="video/mp4">
        Váš prehliadač nepodporuje video tag.
    </video>

    <div class="login-box">
        <h1>LOGIN</h1>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Emailová adresa" required>
            <input type="password" name="password" placeholder="Heslo" required>
            <button type="submit" class="btn-vstup">Vstúpiť do Beast Mode</button>
        </form>

        <div class="links">
            <a href="registracia.php">Ešte nemáš účet? Zaregistruj sa tu</a>
        </div>
    </div>

</body>
</html>