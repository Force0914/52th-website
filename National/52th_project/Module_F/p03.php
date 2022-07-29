<?php
$num = readline();
$data = [];
$i = 0;
$ans = [];
while($i<$num){
    $data[] = readline();
    $i++;
}
foreach ($data as $bla){
    $blanum = 0;
    for ($j=1;$j<$bla;$j++){
        if ($bla%$j==0) $blanum+=$j;
    }
    $ans[$bla] = $blanum;
}
foreach ($ans as $key => $value){
    print ($key == $value ? "Y" : "N");
    print ("\n");
}