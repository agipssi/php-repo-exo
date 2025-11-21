<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php-errors.log');

if (!file_exists(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0777, true);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Debug - Diagnostic</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            background: #1e1e1e;
            color: #d4d4d4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #252526;
            padding: 20px;
            border-radius: 8px;
        }
        h1 {
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        h2 {
            color: #2196F3;
            margin-top: 30px;
        }
        .success {
            background: #1b5e20;
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #4CAF50;
        }
        .error {
            background: #b71c1c;
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #f44336;
        }
        .warning {
            background: #e65100;
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #FF9800;
        }
        .info {
            background: #01579b;
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #2196F3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #3e3e42;
        }
        th {
            background: #2d2d30;
            color: #4CAF50;
        }
        tr:hover {
            background: #2d2d30;
        }
        .back {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .refresh {
            display: inline-block;
            padding: 10px 20px;
            background: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            margin-left: 10px;
        }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            border-left: 4px solid #4CAF50;
        }
        .folder-test {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            border-radius: 4px;
        }
        .test-ok {
            background: #1b5e20;
            border: 1px solid #4CAF50;
        }
        .test-error {
            background: #b71c1c;
            border: 1px solid #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back">← Retour à l'accueil</a>
        <a href="debug.php" class="refresh">actu</a>
        
        <h1>logs</h1>
        
        <h2>Info PHP</h2>
        <div class="info">
            <strong>Version PHP :</strong> <?= phpversion() ?><br>
            <strong>Dossier actuel :</strong> <?= __DIR__ ?><br>
            <strong>Serveur :</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Serveur PHP intégré' ?><br>
            <strong>Display Errors :</strong> <?= ini_get('display_errors') ? 'ON' : 'OFF' ?><br>
            <strong>Error Reporting :</strong> <?= error_reporting() ?><br>
            <strong>Log Errors :</strong> <?= ini_get('log_errors') ? 'ON' : 'OFF' ?><br>
            <strong>Error Log File :</strong> <?= ini_get('error_log') ?: 'Non défini' ?>
        </div>

        <h2>check folder php</h2>
        <?php
        $expected_folders = [
            '1.1', '1.2', '1.3', '1.4',
            '2.1', '2.2', '2.3',
            '3',
            '4.1', '4.2', '4.3',
            '5', '6',
            '7.1', '7.2',
            '8', '9'
        ];
        
        $folder_results = [];
        foreach ($expected_folders as $folder) {
            $exists = is_dir(__DIR__ . '/' . $folder);
            $has_index = file_exists(__DIR__ . '/' . $folder . '/index.php');
            $readable = $exists && is_readable(__DIR__ . '/' . $folder);
            
            $folder_results[] = [
                'folder' => $folder,
                'exists' => $exists,
                'has_index' => $has_index,
                'readable' => $readable,
                'status' => ($exists && $has_index && $readable) ? 'ok' : 'error'
            ];
        }
        ?>
        
        <div style="margin: 20px 0;">
            <?php foreach ($folder_results as $result): ?>
                <div class="folder-test <?= $result['status'] === 'ok' ? 'test-ok' : 'test-error' ?>">
                    <?= $result['status'] === 'ok' ? '✅' : '❌' ?> 
                    <?= $result['folder'] ?> 
                    <?php if ($result['status'] !== 'ok'): ?>
                        <?php if (!$result['exists']): ?>
                            (dossier inexistant)
                        <?php elseif (!$result['has_index']): ?>
                            (index.php manquant)
                        <?php elseif (!$result['readable']): ?>
                            (non lisible)
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>liste des folders</h2>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Nom</th>
                    <th>Permissions</th>
                    <th>Taille</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $files = scandir(__DIR__);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;
                    
                    $path = __DIR__ . '/' . $file;
                    $type = is_dir($path) ? '📁 Dossier' : '📄 Fichier';
                    $perms = substr(sprintf('%o', fileperms($path)), -4);
                    $size = is_file($path) ? number_format(filesize($path)) . ' octets' : '-';
                    
                    
                    $is_tp_folder = is_dir($path) && in_array($file, $expected_folders);
                    $has_index = $is_tp_folder && file_exists($path . '/index.php');
                    
                    echo "<tr>";
                    echo "<td>$type</td>";
                    echo "<td>$file";
                    if ($is_tp_folder) {
                        echo $has_index ? " ✅" : " ❌ (index.php manquant)";
                    }
                    echo "</td>";
                    echo "<td>$perms</td>";
                    echo "<td>$size</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>🔗 Test des liens des TPs</h2>
        <?php foreach ($expected_folders as $folder): ?>
            <?php
            $url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/$folder/";
            $exists = is_dir(__DIR__ . '/' . $folder);
            $has_index = file_exists(__DIR__ . '/' . $folder . '/index.php');
            ?>
            <div class="<?= ($exists && $has_index) ? 'success' : 'error' ?>">
                <?= ($exists && $has_index) ? '✅' : '❌' ?>
                <strong><?= $folder ?></strong> : 
                <a href="<?= $url ?>" target="_blank" style="color: #4CAF50;"><?= $url ?></a>
                <?php if (!$exists): ?>
                    - ⚠️ Dossier inexistant
                <?php elseif (!$has_index): ?>
                    - ⚠️ Fichier index.php manquant
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <h2>📝 Logs d'erreurs PHP</h2>
        <?php
        $log_file = __DIR__ . '/logs/php-errors.log';
        if (file_exists($log_file)) {
            $log_content = file_get_contents($log_file);
            if (!empty(trim($log_content))) {
                echo "<pre>" . htmlspecialchars($log_content) . "</pre>";
                echo "<p><a href='clear-logs.php' style='color: #f44336;'>🗑️ Vider les logs</a></p>";
            } else {
                echo "<div class='success'>Aucune erreur enregistrée</div>";
            }
        } else {
            echo "<div class='info'> Fichier de logs pas encore créé</div>";
        }
        ?>

        <h2>🧪 Test de création de fichier</h2>
        <?php
        $test_file = __DIR__ . '/logs/test-write.txt';
        $can_write = @file_put_contents($test_file, 'Test d\'écriture : ' . date('Y-m-d H:i:s'));
        if ($can_write !== false) {
            echo "<div class='success'> Le serveur peut écrire des fichiers</div>";
            @unlink($test_file);
        } else {
            echo "<div class='error'> Le serveur ne peut pas écrire de fichiers (problème de permissions)</div>";
        }
        ?>

        <h2>🌐 Variables $_SERVER importantes</h2>
        <table>
            <tr>
                <th>Variable</th>
                <th>Valeur</th>
            </tr>
            <tr>
                <td>DOCUMENT_ROOT</td>
                <td><?= $_SERVER['DOCUMENT_ROOT'] ?? 'Non défini' ?></td>
            </tr>
            <tr>
                <td>SCRIPT_FILENAME</td>
                <td><?= $_SERVER['SCRIPT_FILENAME'] ?? 'Non défini' ?></td>
            </tr>
            <tr>
                <td>REQUEST_URI</td>
                <td><?= $_SERVER['REQUEST_URI'] ?? 'Non défini' ?></td>
            </tr>
            <tr>
                <td>HTTP_HOST</td>
                <td><?= $_SERVER['HTTP_HOST'] ?? 'Non défini' ?></td>
            </tr>
        </table>

        <h2>🔧 Extensions PHP chargées</h2>
        <div class="info">
            <?php
            $extensions = get_loaded_extensions();
            sort($extensions);
            foreach ($extensions as $ext) {
                echo "<span style='display: inline-block; margin: 3px; padding: 5px 10px; background: #2d2d30; border-radius: 3px;'>$ext</span> ";
            }
            ?>
        </div>

        <h2>💡 Recommandations</h2>
        <?php
        $recommendations = [];
        
        $missing_folders = array_filter($folder_results, function($r) { return $r['status'] === 'error'; });
        if (count($missing_folders) > 0) {
            $recommendations[] = " Certains dossiers de TPs sont manquants ou incomplets. Exécute <a href='setup.php' style='color: #4CAF50;'>setup.php</a> pour les créer automatiquement.";
        }
        
        if (!is_writable(__DIR__)) {
            $recommendations[] = " Le dossier n'est pas accessible en écriture. Exécute : <code>chmod -R 755 " . __DIR__ . "</code>";
        }
        
        if (!ini_get('display_errors')) {
            $recommendations[] = " L'affichage des erreurs est désactivé. Pour le développement, tu peux l'activer en ajoutant <code>ini_set('display_errors', 1);</code> au début de tes fichiers.";
        }
        
        if (empty($recommendations)) {
            echo "<div class='success'> Tout semble correct !</div>";
        } else {
            foreach ($recommendations as $rec) {
                echo "<div class='warning'>$rec</div>";
            }
        }
        ?>
    </div>
</body>
</html>