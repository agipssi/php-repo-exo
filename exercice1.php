<?php

function school($age) {
    if ($age < 3) {
        return "creche";
    } elseif ($age < 6) {
        return "maternelle";
    } elseif ($age < 11) {
        return "primaire";
    } elseif ($age < 16) {
        return "college";
    } elseif ($age < 18) {
        return "lycee";
    } else {
        return "";
    }
}

echo "Âge 2 : " . school(2) . "<br>";     
echo "Âge 5 : " . school(5) . "<br>";      
echo "Âge 8 : " . school(8) . "<br>";      
echo "Âge 13 : " . school(13) . "<br>";    
echo "Âge 17 : " . school(17) . "<br>";    
echo "Âge 20 : " . school(20) . "<br>";    
?>
