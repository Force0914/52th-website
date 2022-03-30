<!doctype html>
<html lang="en">
<head>
    <?php include('head.php')?>
</head>
<body>
<div id="app" class="container text-center">
    <img src="img/logo.png" class="logo">
    <h1>會員網站後台管理模組</h1>
    <tablet class="tablet">
        <thead>
            <tr>
                <td>#</td>
                <td>帳號</td>
                <td>權限</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in userdata">
                <td>{{index +1}}</td>
                <td>{{item.account}}</td>
                <td>{{item.groups == "admin" ? "管理員" : "使用者"}}</td>
                <td></td>
            </tr>
        </tbody>
    </tablet>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userid: <?=$_SESSION['userid']?>,
                userdata: []
            }
        },
        methods{
          userload(){
          }
        },
        mounted(){
            this.userload()
        }
    }).mount("#app")
</script>
</body>
</html>