<?php
$n = readline();
$ans = [];
for ($i = 0; $i < $n; $i++) {
    $a = readline();
    $b = [];
    for ($j = 1; $j < $a;$j++){
        if ($a % $j == 0) $b[] = $j;
    }
    $ans[] = array_sum($b) == $a ? "Y" : "N";
}
foreach ($ans as $value){
    echo $value;
    echo "\n";
}