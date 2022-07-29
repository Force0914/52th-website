<?php
$num = readline();
$data = [];
$ans = [];
$i=0;
while($i<$num){
    $data[] = readline();
    $i++;
}

foreach ($data as $s){
    print (check($s) ? "Y" : "N")."\n";
}

function check($s)
{
    $left = [];
    $star = [];
    for ($i = 0; $i < strlen($s); $i++) {
        if ($s[$i] == "(") $left[] = $i;
        elseif ($s[$i] == "*") $star[] = $i;
        elseif (!empty($left) && $s[$i] == ")") array_pop($left);
        elseif (!empty($star) && $s[$i] == ")") array_pop($star);
        else return false;
    }
    while (!empty($left) && !empty($star)) {
        if (array_slice($left, 0, 1)[0] < array_slice($star, 0, 1)[0]) {
            array_pop($left);
            array_pop($star);
        } else return false;
    }
    return empty($left);
}