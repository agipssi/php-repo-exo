<?php


function my_str_contains($haystack, $needle) {
    if ($needle === '') {
        return true;
    }
    
    $haystackLen = strlen($haystack);
    $needleLen = strlen($needle);
    
    if ($needleLen > $haystackLen) {
        return false;
    }
    
    for ($i = 0; $i <= $haystackLen - $needleLen; $i++) {
        $found = true;
        
        for ($j = 0; $j < $needleLen; $j++) {
            if ($haystack[$i + $j] !== $needle[$j]) {
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

echo "<h2>TP 4.2 - my_str_contains (bonus)</h2>";
echo "<pre>";

$tests = [
    ["Bonjour le monde", "monde", true],
    ["Bonjour le monde", "Bonjour", true],
    ["Bonjour le monde", "python", false],
    ["PHP est génial", "PHP", true],
    ["PHP est génial", "génial", true],
    ["PHP est génial", "Java", false],
    ["test", "", true],
    ["", "test", false],
    ["", "", true],
];

foreach ($tests as $test) {
    $haystack = $test[0];
    $needle = $test[1];
    $expected = $test[2];
    $result = my_str_contains($haystack, $needle);
    
    $status = ($result === $expected) ? " PASS" : " FAIL";
    echo $status . " | my_str_contains(\"$haystack\", \"$needle\") = " . ($result ? "true" : "false") . "\n";
}

echo "</pre>";

echo "<h3>Comparaison avec str_contains() native</h3>";
echo "<pre>";
if (function_exists('str_contains')) {
    $testStr = "Bonjour le monde PHP";
    $searches = ["monde", "Python", "PHP"];
    
    foreach ($searches as $search) {
        $native = str_contains($testStr, $search);
        $custom = my_str_contains($testStr, $search);
        $match = ($native === $custom) ? "V" : "X";
        
        echo "$match Recherche de '$search' dans '$testStr':\n";
        echo "   - str_contains() : " . ($native ? "true" : "false") . "\n";
        echo "   - my_str_contains() : " . ($custom ? "true" : "false") . "\n\n";
    }
} else {
    echo "La fonction str_contains() n'est pas disponible (PHP < 8.0)\n";
}
echo "</pre>";
?>