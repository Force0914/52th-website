<!doctype html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
    <script src="js/vue.js"></script>
</head>
<body>
<div class="text-center" id="app">
    <img src="img/logo.png" class="logo">
    <h1>會員網站後台管理模組</h1>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                users:[]
            }
        },
        methods:{
            userlist(){
                const _this = this
                $.get('api.php?do=userlist',function (a){
                    _this.users = JSON.parse(a)
                })
            }
        },
        mounted(){
            this.userlist()
        }
    }).mount("#app")
</script>
</body>
</html>