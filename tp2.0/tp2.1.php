<?php

function calcMoy($tableau) {
    $somme = 0;
    $count = 0;
    
    for ($i = 0; $i < count($tableau); $i++) {
        $somme = $somme + $tableau[$i];
        $count++;
    }
    
    if ($count == 0) {
        return 0;
    }
    
    return $somme / $count;
}
echo "Moyenne de [10, 20, 30] : " . calcMoy([10, 20, 30]) . "<br>";  
echo "Moyenne de [5, 10, 15, 20] : " . calcMoy([5, 10, 15, 20]) . "<br>"; 
echo "Moyenne de [100, 50, 75] : " . calcMoy([100, 50, 75]) . "<br>"; 
echo "Moyenne de [8, 12, 16, 20, 24] : " . calcMoy([8, 12, 16, 20, 24]) . "<br>"; 
?>