<?php
//未完成 測資2 第一筆跑測試有問題
$num = readline();
$data = [];
$ans = [];
$i= 0;
while($i<$num){
    $ans[] = check(readline());
    $i++;
}

foreach ($ans as $bla){
    print $bla."\n";
}

function check($num){
    $test = "";
    $num = join("",explode(" ",$num));
    if (!is_numeric($num)) return "N";
    $checknum = $num[-1];
    $newnum = substr($num,0,strlen($num)-1);
    $ans = 0;
    for($i=0;$i<strlen($newnum);$i++){
        if($i%2!=0){
            $numa = $newnum[$i] * 2;
            $numb = 0;
            for ($j=0;$j<strlen($numa);$j++){
                $numb += strval($numa)[$j];
            }
            $ans += $numb;
            $test = $test."+".$numb;
        }else{
            $test = $test."+".$newnum[$i];
            $ans += $newnum[$i];
        }
    }
    $ans *= 9 ;
    print $test."\n";
    return strval($ans)[-1] == $checknum ? "Y" : "N";
}