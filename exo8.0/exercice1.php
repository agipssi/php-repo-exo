<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($username === '') {
        $error = 'Le champ username est obligatoire pour l\'inscription';
    } elseif ($password === '') {
        $error = 'Le champ password est obligatoire pour l\'inscription';
    } else {

        $stmt = $dbh->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingUser) {
            $error = 'Ce nom d\'utilisateur est déjà utilisé';
        } else {
            
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            
            $stmt = $dbh->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
            $stmt->execute([
                'username' => $username,
                'password' => $hash,
            ]);
            
            $success = 'Votre inscription est validée';
        }
    }
}

if (isset($_POST['connect'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($username === '') {
        $error = 'Le champ username est obligatoire pour la connexion';
    } elseif ($password === '') {
        $error = 'Le champ password est obligatoire pour la connexion';
    } else {
        
        $stmt = $dbh->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $error = 'Le nom d\'utilisateur n\'existe pas';
        } elseif (!password_verify($password, $user['password'])) {
            $error = 'Le mot de passe est invalide';
        } else {
            
            $_SESSION['username'] = $user['username'];
        }
    }
}

if (isset($_POST['logout'])) {
    unset($_SESSION['username']);
    session_destroy();
}

$dbh = null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP 8 - Authentification complète</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .forms-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .form-section {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .form-section h2 {
            color: #4CAF50;
            margin-top: 0;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .welcome {
            text-align: center;
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 30px;
        }
        .logout-btn {
            background-color: #f44336;
        }
        .logout-btn:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>TP 8 - Authentification complète</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (!isset($_SESSION['username'])): ?>
            <div class="forms-container">
                <div class="form-section">
                    <h2>Inscription</h2>
                    <form method="post" action="">
                        <label>Username :</label>
                        <input type="text" name="username" required>
                        
                        <label>Password :</label>
                        <input type="password" name="password" required>
                        
                        <input type="submit" name="register" value="S'inscrire">
                    </form>
                </div>
                
                <div class="form-section">
                    <h2>Connexion</h2>
                    <form method="post" action="">
                        <label>Username :</label>
                        <input type="text" name="username" required>
                        
                        <label>Password :</label>
                        <input type="password" name="password" required>
                        
                        <input type="submit" name="connect" value="Se connecter">
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="welcome">
                Bienvenue <?= htmlspecialchars($_SESSION['username']) ?> !
            </div>
            <form method="post" action="">
                <input type="submit" name="logout" value="Se déconnecter" class="logout-btn">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>