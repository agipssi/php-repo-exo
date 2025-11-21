<?php

$contenu = file_get_contents("table.txt");
$lignes = explode("\n", trim($contenu));

$erreurs = [];

foreach ($lignes as $indexLigne => $ligne) {
    if ($indexLigne === 0) continue; 
    
    $valeurs = preg_split('/\s+/', trim($ligne));
    
    if (count($valeurs) < 2) continue;
    
    $multiplicateur = (int)$valeurs[0];
    
    for ($i = 1; $i < count($valeurs); $i++) {
        $resultatFichier = (int)$valeurs[$i];
        $resultatAttendu = $multiplicateur * $i;
        
        if ($resultatFichier !== $resultatAttendu) {
            $erreurs[] = $multiplicateur . "x" . $i;
        }
    }
}

if (count($erreurs) > 0) {
    echo "Les erreurs sont " . implode(", ", $erreurs) . "\n";
} else {
    echo "Aucune erreur trouvée\n";
}
?>