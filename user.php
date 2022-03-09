<!doctype html>
<html lang="en">
<head>
    <?php include("header.php");?>
    <title>TODO 工作管理系統</title>
</head>
<body>
<div id="app">
    <div class="container text-center">
        <h1>{{this.date.getMonth()+1}} 月 {{this.date.getDate()}} 日工作計劃表</h1>
        </div>
        <table class="table">
            <thead>
            <tr>
                <td style="width: 45px">時間</td>
                <td>工作計畫</td>
            </tr>
            </thead>
            <tr v-for="i in 23" @dragover.prevent @dragenter.prevent>
                <td>{{bla(i-1)}}-{{bla(i+1)}}</td>
                <td><div class="block" draggable="true"></td>
            </tr>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data() {
            return {
                date: new Date()
            }
        },
        methods:{
            logout(){
                $.get("api.php?do=logout",function (){})
                alert("登出成功");
                location.href="index.php"
            },
            bla(num) {
                if (num <=9){
                    num = "0" + num.toString()
                }
                return num.toString()
            }
        },
        mounted(){
        }
    }).mount('#app')
</script>
</body>
</html>