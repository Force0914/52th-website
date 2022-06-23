<?php
$n = readline();
$a = [];
$b = [];
$ans = [];
for ($i = 0; $i < $n; $i++) {
    $a[] = readline();
}
$b = array_count_values($a);
foreach ($b as $key => $value){
    if (max($b) == $value){
        $ans[] = $key;
    }
}
if (count($ans) == $n){
    echo -1;
    echo "\n";
    die();
}
sort($ans);
foreach ($ans as $value){
    echo $value;
    echo "\n";
}