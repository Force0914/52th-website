<!doctype html>
<html lang="en">
<head>
    <?php include ('head.php')?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="login">
    <div class="login-block text-center">
        <img src="img/logo.png" class="logo">
        <h2>登入失敗</h2>
        <b class="text-error">登入資訊輸入連續誤錯3次</b>
        <input type="button" class="btn" value="返回" @click="back()">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        methods:{
            back(){
                location.href = "index.php"
            }
        }
    }).mount("#app")
</script>
</body>
</html>