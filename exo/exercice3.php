<?php

function doubleBoucle($n) {
    for ($i = 1; $i <= $n; $i++) {
        for ($j = 1; $j <= $i; $j++) {
            echo $i;
        }
        echo "<br>";
    }
}
doubleBoucle(5);

?>
