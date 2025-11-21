<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php-errors.log');

if (!file_exists(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0777, true);
}

$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$base_url = rtrim($base_url, '/');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des TPs PHP</title>
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
        
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 50px;
            font-size: 3.5em;
            font-weight: 700;
            letter-spacing: -1px;
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
        
        .section {
            margin-bottom: 60px;
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
        
        .section h2 {
            color: #fff;
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, #667eea 0%, #764ba2 100%) 1;
            position: relative;
        }
        
        .section h2::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #764ba2 0%, #667eea 100%);
            transition: width 0.5s ease;
        }
        
        .section:hover h2::after {
            width: 100%;
        }
        
        .tp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }
        
        @media (max-width: 768px) {
            .tp-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .tp-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .tp-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }
        
        .tp-card:hover::before {
            left: 100%;
        }
        
        .tp-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            border-color: rgba(102, 126, 234, 0.5);
        }
        
        .tp-card h2 {
            color: #667eea;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.5em;
            font-weight: 600;
            border: none;
            padding: 0;
        }
        
        .tp-card h2::after {
            display: none;
        }
        
        .tp-card p {
            color: #b8b9d4;
            margin: 15px 0;
            font-size: 0.95em;
            line-height: 1.6;
        }
        
        .tp-card a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85em;
            position: relative;
            overflow: hidden;
        }
        
        .tp-card a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .tp-card a:hover::before {
            left: 100%;
        }
        
        .tp-card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
        }
        
        .debug-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            margin-top: 50px;
            padding: 40px;
            border-radius: 20px;
            animation: fadeIn 1s ease;
        }
        
        .debug-section h3 {
            color: #fff;
            font-size: 1.8em;
            margin-bottom: 25px;
            font-weight: 700;
        }
        
        .debug-section a {
            display: inline-block;
            padding: 14px 30px;
            margin: 10px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9em;
        }
        
        .debug-section a:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.5);
        }
        
        .instructions {
            text-align: center;
            margin-top: 50px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            animation: fadeIn 1.2s ease;
        }
        
        .instructions h3 {
            color: #fff;
            margin-bottom: 20px;
            font-size: 1.8em;
            font-weight: 700;
        }
        
        .instructions p {
            color: #b8b9d4;
            margin: 12px 0;
            font-size: 1.05em;
            line-height: 1.6;
        }
        
        .instructions strong {
            color: #667eea;
            font-weight: 700;
        }
        
        .url-info {
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 20px;
            padding: 15px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            font-family: 'Courier New', monospace;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5em;
            }
            
            .section h2 {
                font-size: 1.5em;
            }
            
            .debug-section a {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Travaux Pratiques PHP</h1>

        <div class="section">
            <h2>Module 1 : Algorithmes de base</h2>
            <div class="tp-grid">
                <div class="tp-card">
                    <h2>TP 1.1 - School</h2>
                    <p>Fonction qui détermine le niveau scolaire selon l'âge</p>
                    <a href="<?= $base_url ?>/exo1.0-1.4/exercice1.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 1.2 - FooBar</h2>
                    <p>Afficher les nombres de 1 à 100 avec Foo, Bar et FooBar</p>
                    <a href="<?= $base_url ?>/exo1.0-1.4/exercice2.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 1.3 - Double boucle</h2>
                    <p>Afficher un motif avec des boucles imbriquées</p>
                    <a href="<?= $base_url ?>/exo1.0-1.4/exercice3.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 1.4 - PGCD</h2>
                    <p>3 algorithmes différents pour calculer le PGCD</p>
                    <a href="<?= $base_url ?>/exo1.0-1.4/exercice4.php">Accéder au TP</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Module 2 : Manipulation de chaînes et tableaux</h2>
            <div class="tp-grid">
                <div class="tp-card">
                    <h2>TP 2.1 - Moyenne</h2>
                    <p>Calculer la moyenne d'un tableau de nombres</p>
                    <a href="<?= $base_url ?>/exo2.0-2.3/exercice1.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 2.2 - my_strrev</h2>
                    <p>Fonction pour inverser une chaîne de caractères</p>
                    <a href="<?= $base_url ?>/exo2.0-2.3/exercice2.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 2.3 - my_str_contains</h2>
                    <p>Vérifier si une chaîne contient une sous-chaîne</p>
                    <a href="<?= $base_url ?>/exo2.0-2.3/exercice3.php">Accéder au TP</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Module 3 : Gestion de fichiers</h2>
            <div class="tp-grid">
                <div class="tp-card">
                    <h2>TP 3 - Carnet d'adresse</h2>
                    <p>Lecture et écriture dans un fichier texte</p>
                    <a href="<?= $base_url ?>/exo3.0/exercice1.php">Accéder au TP</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Module 4 : Tableaux multidimensionnels</h2>
            <div class="tp-grid">
                <div class="tp-card">
                    <h2>TP 4.1 - Moyennes élèves</h2>
                    <p>Calculer les moyennes d'élèves avec tableaux 2D</p>
                    <a href="<?= $base_url ?>/exo4.0-4.2/exercice1.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 4.2 - my_str_contains (bonus)</h2>
                    <p>Version améliorée de la recherche de sous-chaîne</p>
                    <a href="<?= $base_url ?>/exo4.0-4.2/exercice2.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 4.3 - Table de multiplication</h2>
                    <p>Vérifier les erreurs dans une table de multiplication</p>
                    <a href="<?= $base_url ?>/exo4.0-4.2/exercice3.php">Accéder au TP</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Module 5 : MySQL et PHP</h2>
            <div class="tp-grid">
                <div class="tp-card">
                    <h2>TP 5 - Affichage MySQL</h2>
                    <p>Afficher les données de la table 100 (Jeux Olympiques)</p>
                    <a href="<?= $base_url ?>/exo5.0/exercice1.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 6 - Tri dynamique</h2>
                    <p>Ajouter des liens de tri sur les colonnes</p>
                    <a href="<?= $base_url ?>/exo6.0/exercice1.php">Accéder au TP</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Module 6 : Sessions et Authentification</h2>
            <div class="tp-grid">
                <div class="tp-card">
                    <h2>TP 7.1 - Session simple</h2>
                    <p>Premier formulaire avec variable de session</p>
                    <a href="<?= $base_url ?>/exo7.0/exercice1.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 7.2 - Session avec déconnexion</h2>
                    <p>Ajouter un bouton de déconnexion</p>
                    <a href="<?= $base_url ?>/exo7.0/exercice2.php">Accéder au TP</a>
                </div>

                <div class="tp-card">
                    <h2>TP 8 - Authentification complète</h2>
                    <p>Système d'inscription et connexion avec MySQL</p>
                    <a href="<?= $base_url ?>/exo8.0/exercice1.php">Accéder au TP</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Module 7 : Projet Final</h2>
            <div class="tp-grid">
                <div class="tp-card">
                    <h2>TP 9 - Système de messagerie</h2>
                    <p>Application complète avec authentification et messages</p>
                    <a href="<?= $base_url ?>/exo9.0/exercice1.php">Accéder au TP</a>
                </div>
            </div>
        </div>

        <div class="debug-section">
            <h3>Outils de développement</h3>
            <a href="<?= $base_url ?>/debug.php">Page de diagnostic</a>
            <a href="<?= $base_url ?>/setup.php">Setup automatique</a>
            <a href="<?= $base_url ?>/test-navigation.php">Test navigation</a>
        </div>

        <div class="instructions">
            <h3>Instructions</h3>
            <p>Clique sur "Accéder au TP" pour tester chaque exercice</p>
            <p><strong>Note :</strong> Les TPs 5, 6, 8 et 9 nécessitent une base de données MySQL</p>
            <p class="url-info">URL de base : <?= $base_url ?></p>
        </div>
    </div>
</body>
</html>