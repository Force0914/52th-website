<?php
header("content-type:image/png");
$img = imagecreate(50,20);
imagecolorallocate($img,245,245,245);
imagestring($img,5,7,0,array_keys($_GET)[0],imagecolorallocate($img,0,0,0));
for($i=0;$i<30;$i++) {
    imagesetpixel($img, rand(0, 50), rand(0, 20), imagecolorallocate($img,rand(0, 255),rand(0, 255),rand(0, 255)));
}
imagepng($img);