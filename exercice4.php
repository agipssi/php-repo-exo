<?php

function pgcd($a, $b) {
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

echo "PGCD(12, 8) = " . pgcd(12, 8) . "<br>";     
echo "PGCD(48, 18) = " . pgcd(48, 18) . "<br>";    
echo "PGCD(100, 50) = " . pgcd(100, 50) . "<br>";  
echo "PGCD(17, 19) = " . pgcd(17, 19) . "<br>";    
echo "PGCD(54, 24) = " . pgcd(54, 24) . "<br>";    
?>
