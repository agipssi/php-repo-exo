<?php

function my_str_contains($haystack, $needle) {
    $haystack_len = strlen($haystack);
    $needle_len = strlen($needle);
    
    if ($needle_len == 0) {
        return true;
    }
    
    if ($needle_len > $haystack_len) {
        return false;
    }
    
    for ($i = 0; $i <= $haystack_len - $needle_len; $i++) {
        $found = true;
        
        for ($j = 0; $j < $needle_len; $j++) {
            if (!isset($haystack[$i + $j]) || !isset($needle[$j]) || $haystack[$i + $j] != $needle[$j]) {
                $found = false;
                break;
            }
        }
        
        if ($found) {
            return true;
        }
    }
    
    return false;
}

echo "Est-ce que 'bonjour' contient 'jour' ? " . (my_str_contains("bonjour", "jour") ? "Oui" : "Non") . "<br>";  // Oui
echo "Est-ce que 'hello world' contient 'world' ? " . (my_str_contains("hello world", "world") ? "Oui" : "Non") . "<br>";  // Oui
echo "Est-ce que 'PHP' contient 'Java' ? " . (my_str_contains("PHP", "Java") ? "Oui" : "Non") . "<br>";  // Non
echo "Est-ce que 'test' contient 'es' ? " . (my_str_contains("test", "es") ? "Oui" : "Non") . "<br>";  // Oui
echo "Est-ce que 'abcdef' contient 'xyz' ? " . (my_str_contains("abcdef", "xyz") ? "Oui" : "Non") . "<br>";  // Non

if (function_exists('str_contains')) {
    echo "<br>Vérification avec str_contains() :<br>";
    $test_haystack = "bonjour";
    $test_needle = "jour";
    echo "my_str_contains('$test_haystack', '$test_needle') = " . (my_str_contains($test_haystack, $test_needle) ? "true" : "false") . "<br>";
    echo "str_contains('$test_haystack', '$test_needle') = " . (str_contains($test_haystack, $test_needle) ? "true" : "false") . "<br>";
}
?>