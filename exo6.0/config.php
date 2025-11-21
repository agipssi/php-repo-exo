<?php

try {
    
    $dbh = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=jo;charset=utf8mb4',
        'root',
        'root123',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false,
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>