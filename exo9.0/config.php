<?php

try {
    $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=tp9;charset=utf8',
        'root',
        'root123'
    );
} catch (PDOException $e) {
    die($e->getMessage());
}

?>