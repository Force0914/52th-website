<?php
session_start();
header("content-type:image/jpeg");
$img = imagecreate(400,100);
imagecolorallocate($img,255,255,255);
$str = array_keys($_GET)[0];
// for ($j=0; $j < 2; $j++) { 
//     $str .= chr(rand(65,90));
// }
// for ($j=0; $j < 3; $j++) { 
//     $str .= rand(0,9);
// }
foreach (str_split($str) as $key => $value) {
    imagettftext($img,80,rand(-20,20),40+$key*80,rand(80,90),imagecolorallocate($img,rand(0,100),rand(0,100),rand(0,100)),'arial',$value);
}
for ($i=0; $i < rand(3,5); $i++) { 
    imageline($img,rand(0,400),rand(0,100),rand(0,400),rand(0,100),imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255)));
}
for ($i=0; $i < rand(3,5); $i++) { 
    imagearc($img,rand(0,400),rand(0,100),rand(0,400),rand(0,100),rand(0,360),rand(0,360),imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255)));
}
imagejpeg($img);