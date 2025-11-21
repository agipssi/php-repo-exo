<?php

try {
    $dbh = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=tp8;charset=utf8mb4',
        'root',
        'root123'
    );
} catch (PDOException $e) {
    die($e->getMessage());
}

?>