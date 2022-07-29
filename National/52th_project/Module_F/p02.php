<?php
$num = readline();
$i = 0;
$data = [];
$count = [];
$ans = [];
while($i<$num){
    $data[] = readline();
    $i++;
}
foreach ($data as $bla){
    $count[$bla] = isset($count[$bla]) ? ++$count[$bla] : 1;
}
foreach ($count as $key => $bla){
    if (max($count) == $bla) $ans[] = $key;
}
asort($ans);
print (sizeof($ans) == sizeof($data) ? "-1" : join("\n",$ans))."\n";
