<?php
session_start();
if (!isset($_SESSION["userid"])){
    header("Location:index.php");
}
if ($_SESSION["groups"] != "admin"){
    header("Location:index.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php include("header.php");?>
    <title>使用者登入登出紀錄</title>
</head>
<body>
<script>
    const { reactive } = Vue
    let vue = Vue.createApp({
        data() {
            return {
                logs: []
            }
        },
        methods:{
            updatedata(){
                const _this = this
                $.get(`api.php?do=userlist`,function (a){
                    _this.users = JSON.parse(a)
                })
            }
        },
        mounted(){
            this.updatedata()
        }
    }).mount('#app')
</script>
</body>
</html>