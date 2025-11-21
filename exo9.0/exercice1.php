<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($username === '') {
        $error = 'Le champ username est obligatoire pour l\'inscription';
    } elseif ($name === '') {
        $error = 'Le champ name est obligatoire pour l\'inscription';
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
            
            $stmt = $dbh->prepare("INSERT INTO user (username, name, password) VALUES (:username, :name, :password)");
            $stmt->execute([
                'username' => $username,
                'name' => $name,
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
            $_SESSION['name'] = $user['name'];
        }
    }
}

if (isset($_POST['send_message']) && isset($_SESSION['username'])) {
    $message = trim($_POST['message'] ?? '');
    
    if ($message !== '') {
        $stmt = $dbh->prepare("INSERT INTO message (name, message) VALUES (:name, :message)");
        $stmt->execute([
            'name' => $_SESSION['name'],
            'message' => $message,
        ]);
        $success = 'Message envoyé avec succès';
    }
}

if (isset($_POST['logout'])) {
    unset($_SESSION['username']);
    unset($_SESSION['name']);
    session_destroy();
}

$stmt = $dbh->prepare("SELECT * FROM message ORDER BY id DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dbh = null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP 9 - Système de messagerie</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f0f1e;
            min-height: 100vh;
            padding: 0;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(74, 86, 226, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(138, 43, 226, 0.2) 0%, transparent 50%);
            animation: gradientShift 15s ease infinite;
            z-index: -1;
        }
        
        @keyframes gradientShift {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 20px;
            font-size: 3em;
            font-weight: 700;
            letter-spacing: -1px;
        }
        
        .forms-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .forms-container {
                grid-template-columns: 1fr;
            }
        }
        
        .form-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.3);
            border-color: rgba(102, 126, 234, 0.5);
        }
        
        .form-section h2 {
            color: #fff;
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.8em;
            font-weight: 600;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #b8b9d4;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        input[type="text"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 14px 18px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 15px;
            color: #fff;
            transition: all 0.3s ease;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus,
        textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.08);
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        input[type="text"]::placeholder,
        input[type="password"]::placeholder,
        textarea::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        
        input[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        input[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        input[type="submit"]:hover::before {
            left: 100%;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
        }
        
        input[type="submit"]:active {
            transform: translateY(0);
        }
        
        .error {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            animation: shake 0.5s ease;
            box-shadow: 0 5px 20px rgba(255, 107, 107, 0.3);
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .success {
            background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);
            color: white;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            animation: slideInRight 0.5s ease;
            box-shadow: 0 5px 20px rgba(81, 207, 102, 0.3);
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .welcome-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease;
        }
        
        .welcome {
            text-align: center;
            font-size: 32px;
            color: #fff;
            margin-bottom: 25px;
            font-weight: 700;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            max-width: 300px;
            margin: 0 auto;
            display: block;
        }
        
        .logout-btn:hover {
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.5);
        }
        
        .messages-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 40px;
            animation: fadeIn 1s ease;
        }
        
        .messages-section h2 {
            color: #fff;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2.2em;
            font-weight: 700;
        }
        
        .message-item {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            animation: slideInLeft 0.5s ease;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .message-item:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
        }
        
        .message-author {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 1.2em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .message-content {
            color: #b8b9d4;
            line-height: 1.7;
            font-size: 15px;
        }
        
        .no-messages {
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            padding: 60px;
            font-size: 1.3em;
        }
        
        .new-message-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1.2s ease;
        }
        
        .new-message-section h2 {
            color: #fff;
            margin-bottom: 25px;
            text-align: center;
            font-size: 2em;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Système de Messagerie</h1>
            
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
                            <label>Username</label>
                            <input type="text" name="username" required>
                            
                            <label>Nom complet</label>
                            <input type="text" name="name" required>
                            
                            <label>Password</label>
                            <input type="password" name="password" required>
                            
                            <input type="submit" name="register" value="S'inscrire">
                        </form>
                    </div>
                    
                    <div class="form-section">
                        <h2>Connexion</h2>
                        <form method="post" action="">
                            <label>Username</label>
                            <input type="text" name="username" required>
                            
                            <label>Password</label>
                            <input type="password" name="password" required>
                            
                            <input type="submit" name="connect" value="Se connecter">
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_SESSION['username'])): ?>
            <div class="welcome-section">
                <div class="welcome">
                    Bienvenue <?= htmlspecialchars($_SESSION['name']) ?>
                </div>
                <form method="post" action="">
                    <input type="submit" name="logout" value="Se déconnecter" class="logout-btn">
                </form>
            </div>
            
            <div class="messages-section">
                <h2>Messages</h2>
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="message-item">
                            <div class="message-author">
                                <?= htmlspecialchars($msg['name']) ?>
                            </div>
                            <div class="message-content">
                                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-messages">
                        Aucun message pour le moment. Soyez le premier à poster !
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="new-message-section">
                <h2>Nouveau Message</h2>
                <form method="post" action="">
                    <textarea name="message" placeholder="Écrivez votre message ici..." required></textarea>
                    <input type="submit" name="send_message" value="Envoyer le message">
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>