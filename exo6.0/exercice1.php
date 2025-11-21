<?php

require_once 'config.php';

$colonnesAutorisees = ['nom', 'pays', 'course', 'temps'];
$ordresAutorises = ['asc', 'desc'];

$sort = "nom";
$order = "asc";

if (isset($_GET['sort']) && in_array($_GET['sort'], $colonnesAutorisees)) {
    $sort = $_GET['sort'];
}

if (isset($_GET['order']) && in_array($_GET['order'], $ordresAutorises)) {
    $order = $_GET['order'];
}

$sth = $dbh->prepare("SELECT * FROM `100` ORDER BY `$sort` $order");
$sth->execute();

$data = $sth->fetchAll(PDO::FETCH_ASSOC);

function getInverseOrder($currentOrder) {
    return $currentOrder === 'asc' ? 'desc' : 'asc';
}

function displayArrow($column, $currentSort, $currentOrder) {
    if ($column === $currentSort) {
        return $currentOrder === 'asc' ? ' ↑' : ' ↓';
    }
    return ' ↕';
}

function getArrowStyle($column, $currentSort) {
    return $column === $currentSort ? 'color: red;' : '';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>JO - 100m</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: black; }
        .active { color: red; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <?php
                $columns = [
                    'nom' => 'Nom',
                    'pays' => 'Pays',
                    'course' => 'Course',
                    'temps' => 'Temps'
                ];
                
                foreach ($columns as $col => $label):
                    $newOrder = ($col === $sort) ? getInverseOrder($order) : 'asc';
                ?>
                    <th>
                        <a href="?sort=<?= $col ?>&order=<?= $newOrder ?>">
                            <?= $label ?>
                            <span style="<?= getArrowStyle($col, $sort) ?>">
                                <?= displayArrow($col, $sort, $order) ?>
                            </span>
                        </a>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $value): ?>
                <tr>
                    <td><?= htmlspecialchars($value["nom"]) ?></td>
                    <td><?= htmlspecialchars($value["pays"]) ?></td>
                    <td><?= htmlspecialchars($value["course"]) ?></td>
                    <td><?= htmlspecialchars($value["temps"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php
$dbh = null;
?>