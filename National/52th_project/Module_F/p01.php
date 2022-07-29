<?php
$num = readline();
$i=0;
$data = [];
$ans = [];
while($i<$num){
    $data[] = readline();
    $i++;
}
for ($i=1;$i<=min($data);$i++){
    $is = true;
    foreach ($data as $bla){
        if (($bla % $i) != 0) $is = false;
    }
    if ($is) $ans[] = $i;
}
print (max($ans)."\n");