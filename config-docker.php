
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {

    $dbh = new PDO(
        'mysql:host=mysql;dbname=;charset=utf8',
        'root',      
        'root123'      
    );
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('<h1>Erreur de connexion MySQL (Docker)</h1>' .
        '<p><strong>Erreur :</strong> ' . $e->getMessage() . '</p>' .
        '<h3>Vérifications Docker :</h3>' .
        '<ul>' .
        '<li>✓ Les containers sont démarrés : <code>docker ps</code></li>' .
        '<li>✓ Le nom du service MySQL dans docker-compose.yml (généralement "mysql" ou "db")</li>' .
        '<li>✓ Le mot de passe correspond à MYSQL_ROOT_PASSWORD</li>' .
        '<li>✓ La base "formation" existe : <code>docker exec -it nom_container mysql -uroot -proot -e "SHOW DATABASES;"</code></li>' .
        '</ul>');
}
