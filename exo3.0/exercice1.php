<?php
 
$contacts = [
    "Alice Dupont",
    "John Doe", 
    "Jean Martin",
    "Marie Leclerc",
    "Pierre Dubois",
    "Sophie Bernard",
    "Lucas Petit",
    "Emma Rousseau",
    "Thomas Moreau",
    "Chloé Lefebvre",
    "Nicolas Girard",
    "Julie Bonnet",
    "Antoine Morel",
    "Laura Simon",
    "Maxime Laurent"
];
 
$filename = "contact.txt";
 
if (!file_exists($filename)) {
    die("pas de fichier :(");
}
 
$existingContacts = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
 
$file = fopen($filename, "a");
 
if (!$file) {
    die("impossible d'ouvrir le fichier :(");
}
 
foreach ($contacts as $contact) {
    if (!in_array($contact, $existingContacts)) {
        fwrite($file, $contact . PHP_EOL);
        echo "on ajoute : $contact<br>";
    } else {
        echo "déjà présent : $contact<br>";
    }
}
 
fclose($file);
?>
 