<?php
if (strpos($_SERVER['HTTP_REFERER'] ?? "", '07.html') !== false) {
    header("content-type:image/jpeg");
    imagejpeg(imagecreatefromjpeg("images/image.jpg"));
}else {
    header("HTTP/1.1 403 Forbidden" );
    die();
}