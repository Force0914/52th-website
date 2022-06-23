<?php
$n = readline();
$a = [];
$ans = 0;
for ($i = 0; $i < $n; $i++) {
    $a[] = readline();
}
foreach ($a as $value){
    $array = array_reverse(str_split(substr($value, 0, -1)));
    foreach ($array as $key => $value2){
        if ($key % 2 == 0){
            foreach (str_split($value2 * 2) as $value3){
                $ans += $value3;
            }
        }else{
            $ans += $value2;
        }
    }
    echo $ans;
    echo "\n";
}