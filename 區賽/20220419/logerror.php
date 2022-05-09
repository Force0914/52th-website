<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="center text-center">
    <div class="login">
        <img src="img/logo.png" class="logo">
        <h1>登入失敗</h1>
        <h3 class="text-error">登入資訊輸入連續誤錯3次</h3>
        <input type="button" value="返回" class="btn" @click="back">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        methods:{
            back(){
                location.href = "index.php"
            }
        },
    }).mount("#app")
    function rand(min,max){
        return Math.floor(Math.random()*(max-min+1))+min
    }
    function chr(chrcode){
        return String.fromCharCode(chrcode)
    }
</script>
</body>
</html>