<!doctype html>
<html lang="en">
<head>
    <?php include ('head.php');?>
</head>
<body>
<div class="login" id="app">
    <div class="login-block text-center">
        <img src="img/logo.png" class="logo">
        <h2>登入失敗</h2>
        <h4 class="text-error">登入資訊輸入連續誤錯3次</h4>
        <input type="button" value="返回" class="btn" style="width: 100%" @click="back()">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        methods:{
            back(){
                location.href="index.php"
            }
        }
    }).mount("#app")
</script>
</body>
</html>