<?php
header("content-type:image/png");
$img = imagecreate(20,20);
imagecolorallocate($img,255,255,255);
imagestring($img,5,7,0,array_keys($_GET)[0],imagecolorallocate($img,0,0,0));
imagepng($img);