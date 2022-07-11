<?php
$num = readline();

$data = [];

foreach(range(1,$num) as $i){
    $in = array_shift($lines);
    $c = 0;
    if(preg_match('/[0-9]/',$in)) $c++;
    if(preg_match('/[A-Z]/',$in)) $c++;
    if(preg_match('/[a-z]/',$in)) $c++;
    if(preg_match('/\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\+|\=|\-|\\|\||\'|\;|\"|\:|\/|\.|\,|\?|\>|\</',$in)) $c++;
    $data[] = $c;
}
foreach($data as $m){
    print $m."\n";
}