<?php
$n = readline();
$a = [];
$ans = [];
for ($i = 0; $i < $n; $i++) {
    $a[] = readline();
}

$number = "0123456789";
$alphabet = "~!@#$%^&*()_+=-\|';\":/.,?><";
$big = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$small = "abcdefghijklmnopqrstuvwxyz";

foreach ($a as $value){
    $ansnum = 0;
    $bla = [
        "number" => false,
        "alphabet" => false,
        "big" => false,
        "small" => false,
    ];
    foreach (str_split($number) as $b){
        if (str_contains($value, $b)) $bla["number"] = true;
    }
    foreach (str_split($alphabet) as $b){
        if (str_contains($value, $b)) $bla["alphabet"] = true;
    }
    foreach (str_split($big) as $b){
        if (str_contains($value, $b)) $bla["big"] = true;
    }
    foreach (str_split($small) as $b){
        if (str_contains($value, $b)) $bla["small"] = true;
    }
    foreach ($bla as $item) {
        if ($item) $ansnum++;
    }
    $ans[] = $ansnum;
}

foreach ($ans as $value){
    echo $value;
    echo "\n";
}