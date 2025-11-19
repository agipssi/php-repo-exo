<?php
try {
    $connexion = new PDO('mysql:host=localhost;dbname=jo;charset=utf8', 'root', '');
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['nom']) && !empty($_POST['pays']) && !empty($_POST['course']) && !empty($_POST['temps'])) {
        $requete = $connexion->prepare("INSERT INTO `100` (nom, pays, course, temps) VALUES (?, ?, ?, ?)");
        $requete->execute([
            strtoupper($_POST['nom']),
            strtoupper($_POST['pays']),
            $_POST['course'],
            $_POST['temps']
        ]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

$colonnesValides = ['nom', 'pays', 'course', 'temps'];
$tri = isset($_GET['sort']) && in_array($_GET['sort'], $colonnesValides) ? $_GET['sort'] : 'nom';
$ordre = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

$requete = $connexion->prepare("SELECT * FROM `100` ORDER BY $tri $ordre");
$requete->execute();
$resultats = $requete->fetchAll(PDO::FETCH_ASSOC);

function genererLienTri($colonne, $triActuel, $ordreActuel) {
    $nouvelOrdre = ($colonne === $triActuel && $ordreActuel === 'ASC') ? 'desc' : 'asc';
    $fleche = '';
    if ($colonne === $triActuel) {
        $fleche = $ordreActuel === 'ASC' ? ' ▲' : ' ▼';
    }
    return "<a href='?sort=$colonne&order=$nouvelOrdre'>" . ucfirst($colonne) . $fleche . "</a>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats 100m</title>
</head>
<body>

<h1>Ajouter un résultat</h1>

<form method="POST">
    <div>
        <label>Nom du coureur :</label><br>
        <input type="text" name="nom" required>
    </div>
    
    <div>
        <label>Pays (3 lettres) :</label><br>
        <input type="text" name="pays" maxlength="3" required>
    </div>
    
    <div>
        <label>Course :</label><br>
        <input type="text" name="course" required>
    </div>
    
    <div>
        <label>Temps (secondes) :</label><br>
        <input type="number" step="0.01" name="temps" required>
    </div>
    
    <button type="submit">Ajouter</button>
</form>

<hr>

<h2>Liste des résultats</h2>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th><?php echo genererLienTri('nom', $tri, $ordre); ?></th>
            <th><?php echo genererLienTri('pays', $tri, $ordre); ?></th>
            <th><?php echo genererLienTri('course', $tri, $ordre); ?></th>
            <th><?php echo genererLienTri('temps', $tri, $ordre); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($resultats as $athlete): ?>
        <tr>
            <td><?php echo htmlspecialchars($athlete['nom']); ?></td>
            <td><?php echo htmlspecialchars($athlete['pays']); ?></td>
            <td><?php echo htmlspecialchars($athlete['course']); ?></td>
            <td><?php echo htmlspecialchars($athlete['temps']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
