<?php

require_once 'config.php';

$sth = $dbh->prepare('SELECT * FROM jo.`100`');
$sth->execute();

$data = $sth->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JO - 100m</title>
</head>
<body>
    <h1>Résultats 100m</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Pays</th>
                <th>Course</th>
                <th>Temps</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $athlete): ?>
            <tr>
                <td><?php echo $athlete['nom']; ?></td>
                <td><?php echo $athlete['pays']; ?></td>
                <td><?php echo $athlete['course']; ?></td>
                <td><?php echo $athlete['temps']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>