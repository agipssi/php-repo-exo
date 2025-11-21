<?php

$eleves = [
    ["nom" => "Alice", "notes" => [15, 14, 16]],
    ["nom" => "Bob", "notes" => [12, 10, 11]],
    ["nom" => "Claire", "notes" => [18, 17, 16]]
];

foreach ($eleves as $eleve) {
    $somme = 0;
    foreach ($eleve["notes"] as $note) {
        $somme += $note;
    }
    $moyenne = $somme / count($eleve["notes"]);
    echo $eleve["nom"] . " : " . number_format($moyenne, 2) . "\n";
}
?>