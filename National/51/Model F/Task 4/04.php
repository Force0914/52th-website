<?php
$m = readline();
$dataA = [];
$dataB = [[]];
$dataC = [];
$ans = [];
for ($i = 0; $i < $m; $i++) {
    $dataA[] = readline();
}

foreach ($dataA as $value){
    $data = explode(" ", $value);
    $dataB[$data[0]] = [
      "a" => $data[1],
      "b" => $data[2],
      "c" => $data[3],
      "d" => $data[4]
    ];
}
array_shift($dataB);
$n = readline();
for ($i = 0; $i < $n; $i++) {
    $dataC[] = readline();
}

foreach ($dataC as $value){
    $data = explode(" ", $value);
    if ($data[0] == "A"){
        if ($data[1] == "TWD"){
            $ans[] = $data[3] / $dataB[$data[2]]["d"];
        }else{
            $ans[] = $data[3] * $dataB[$data[1]]["c"];
        }
    }else{
        if ($data[1] == "TWD"){
            $ans[] = $data[3] / $dataB[$data[2]]["b"];
        }else{
            $ans[] = $data[3] * $dataB[$data[1]]["a"];
        }
    }
}

foreach ($ans as $value){
    echo number_format($value, 5,".","");
    echo "\n";
}