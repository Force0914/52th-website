<?php
$n = readline();
$a = [];
$ans = 0;
for ($i = 0; $i < $n; $i++) {
    $a[] = readline();
}
for ($i = min($a); $i >= 1; $i--){
    $b = false;
    if ($ans != 0) continue;
    foreach ($a as $value){
        if ($value % $i != 0){
            $b = true;
        }
    }
    if ($b == false){
        $ans = $i;
        echo $ans;
        echo "\n";
    }
}