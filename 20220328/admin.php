<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
</head>
<body>
<div id="app" class="container">
    <h1>會員網站後台管理模組</h1>
    <table class="table">
        <thead>
        <tr>
            <td>#</td>
            <td>帳號</td>
            <td>權限</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in users">
            <td>{{index + 1}}</td>
            <td>{{item.account}}</td>
            <td>{{item.groups == "admin" ? "管理者" : "使用者"}}</td>
            <td>
                <div class="btn-group">
                    <button class="btn dropdown-toggle" data-toggle="dropdown">管理員功能
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                            <a tabindex="-1">blabla</a>
                            <ul class="dropdown-menu">
                                <li><a>blabla</a></li>
                            </ul>
                        </li>
                        <li><a>bla</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div id="blaModal" class="modal fade hide" tabindex="-1" aria-labelledby="">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                users:[]
            }
        },
        methods:{
            userdata(){
                const _this = this
                $.get('api.php?do=userlist',function (e) {
                    _this.users = JSON.parse(e)
                })
            },
            createuser(){

            },
            edituser(){

            },
            deluser(){

            }
        },
        mounted(){
            this.userdata()
        }
    }).mount("#app")
</script>
</body>
</html>