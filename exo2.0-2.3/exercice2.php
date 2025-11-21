<?php

function my_strrev($str) {
    $result = "";
    $length = strlen($str);
    
    for ($i = $length - 1; $i >= 0; $i--) {
        if (isset($str[$i])) {
            $result = $result . $str[$i];
        }
    }
    
    return $result;
}

echo "Inverser 'bonjour' : " . my_strrev("bonjour") . "<br>"; 
echo "Inverser 'hello' : " . my_strrev("hello") . "<br>"; 
echo "Inverser 'PHP' : " . my_strrev("PHP") . "<br>"; 
echo "Inverser '12345' : " . my_strrev("12345") . "<br>";  
echo "Inverser 'a' : " . my_strrev("a") . "<br>";  

echo "<br>Vérification :<br>";
$test = "bonjour";
echo "my_strrev('$test') = " . my_strrev($test) . "<br>";
echo "strrev('$test') = " . strrev($test) . "<br>";
?>