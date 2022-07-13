<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <img src="">
    <label style="
        font-size: 48px;
        font-weight: bold;
        color: #000;
    "></label>
    <input type="text" id="captcha">
    <input type="submit" value="送出" id="submit">
    <script>
        let str = ""
        window.onload = function(){
            const image = document.querySelector('img');
            for (i = 0; i < 4; i++) {
                    switch (rand(1,2)) {
                        case 1:
                            str += rand(0,9)
                            break
                        case 2:
                            str += chr(rand(65,90))
                            break
                    }
            }
            image.src = `06.php?${str}`;
        }
        const submit = document.getElementById('submit');
        submit.onclick = function(){
            const captcha = document.getElementById('captcha').value;
            const label = document.querySelector('label');
            if (captcha !== str) {
                label.style.color = '#880000';
                label.innerHTML = '失敗';
            }else{
                label.style.color = '#008800';
                label.innerHTML = '成功';
            }
        }
        function rand(min,max){
            return Math.floor(Math.random()*(max-min+1))+min
        }
        function chr(chrcode){
            return String.fromCharCode(chrcode)
        }
    </script>
</body>
</html>