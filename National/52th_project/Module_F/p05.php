<?php
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
        }else{
            $ans += $newnum[$i];
        }
    }
    $ans *= 9 ;
    return strval($ans)[-1] == $checknum ? "Y" : "N";
}