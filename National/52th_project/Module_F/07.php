<?php
$num = readline();
$data = [];

foreach(range(1,$num) as $i){
    $nn = readline();
    $t = explode(" ",readline());
    $ar = [];
    foreach(range(1,$nn) as $j){
        $ar[] = $t[$j-1];
    }
    $abc=[];
    permutation($ar);
    $data[] = sortAndFindNextOneOrMinimum($abc,implode('',$t));
}
foreach($data as $m){
    print $m."\n";
}

function permutation($data = [],$ar = []){
    global $abc;

    if(count($data) == 0) $abc[] = implode('',$ar);

    foreach($data as $k => $v){
        $new_data = $data;
        $new_ar = $ar;

        array_splice($new_data,$k,1);
        $new_ar[] = $v;

        permutation($new_data,$new_ar);
    }
}

function sortAndFindNextOneOrMinimum($data,$value){
    sort($data);
    $ar = array_unique($data);
    $ar = array_values($ar);
    $index = array_search($value,$ar);
    return $ar[($index + 1) % count($ar)];
}