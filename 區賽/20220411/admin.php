<!doctype html>
<html lang="en">
<head>
    <?php include ("head.php");?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="text-center">
    <img src="img/logo.png" class="logo">
    <h1>會員網站後台管理模組</h1>
    <input type="button" value="登出" class="btn logout" @click="logout()">
    <input type="button" class="btn" value="會員新增" @click="adduser()">
    <div class="table-border">
        <table class="table">
            <thead>
                <tr>
                    <td>#</td>
                    <td>帳號</td>
                    <td>密碼</td>
                    <td>權限</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in users">
                    <td>{{index + 1}}</td>
                    <td>{{item.account}}</td>
                    <td>{{item.groups == "admin" ? "管理者" : "使用者"}}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                管理者功能
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a v-show="item.id != userid" @click="edituser(index)">修改</a></li>
                                <li><a @click="userlogshow(item.id)">登入登出紀錄</a></li>
                                <li><a v-show="item.id != userid" @click="deluser(item.id)">刪除</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="editmodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>會員編輯</h3>
        </div>
        <div class="modal-body">
            <input type="text" placeholder="帳號" class="newinput" v-model="edit.account">
            <input type="text" placeholder="密碼" class="newinput fakepassword" v-model="edit.password">
            <select v-model="edit.groups">
                <option value="admin">管理者</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="save()">儲存</button>
            <button class="btn" data-dismiss="modal" @click="userlist()">取消</button>
        </div>
    </div>
    <div id="userlogmodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>登入登出紀錄</h3>
        </div>
        <div class="modal-body">
            <table class="table">
                <thead>
                <tr>
                    <td>#</td>
                    <td>系統訊息</td>
                    <td>時間</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in userlog">
                    <td>{{index +1}}</td>
                    <td>{{item.action}}</td>
                    <td>{{item.time}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userid:<?=$_SESSION['userid']?>,
                users:[],
                userlog:[],
                edit:{}
            }
        },
        methods:{
            userlist(){
                const _this = this
                $.get('api.php?do=userlist',function (a){
                    _this.users = JSON.parse(a)
                })
            },
            adduser(){
                this.edit = {
                    id: -1,
                    account: "",
                    password : "",
                    groups: "user"
                }
                $("#editmodal").modal('show')
            },
            edituser(idx){
                this.edit = this.users[idx]
                $("#editmodal").modal('show')
            },
            save(){
                let c = false
                this.users.forEach((e)=>{
                    if (this.edit.account == e.account && this.edit.id != e.id) c = true
                })
                if (c) return alert("該使用者已存在")
                if (this.edit.id == -1){
                    $.post('api.php?do=adduser',this.$data.edit,function (){})
                }else {
                    $.post('api.php?do=edituser',this.$data.edit,function (){})
                    if (this.edit.id == this.userid && this.edit.groups == "user") {
                        $.get('api.php?do=logout',function (){})
                        location.href = "index.php"
                    }
                }
                this.userlist()
            },
            logout(){
                $.get('api.php?do=logout',function (){})
                alert("登出成功")
                location.href="index.php"
            },
            deluser(id){
                $.post('api.php?do=deluser',{id:id},function (){})
                alert("刪除成功")
                this.userlist()
                if (id == this.userid) location.href="index.php"
            },
            userlogshow(id){
                const _this = this
                $.post('api.php?do=userlog',{id:id},function (a){
                    _this.userlog = JSON.parse(a)
                })
                $("#userlogmodal").modal('show')
            }
        },
        mounted(){
            this.userlist()
        }
    }).mount("#app")
</script>
</body>
</html>