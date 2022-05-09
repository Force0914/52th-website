<!doctype html>
<html lang="en">
<head>
    <?php include('head.php')?>
</head>
<body>
<div id="app" class="container text-center">
    <img src="img/logo.png" class="logo">
    <h1>會員網站後台管理模組</h1>
    <input type="button" value="登出" @click="logout()" class="btn logout">
    <button class="btn" @click="adduserModal()">新增會員</button>
    <div class="table-border">
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
                    <div class='btn-group'>
                        <button class="btn dropdown-toggle" data-toggle="dropdown">
                            管理員功能
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a @click="edituserModal(index)">編輯會員</a></li>
                            <li><a @click="userlogModal(item.id)">登入登出紀錄</a></li>
                            <li><a @click="deluser(item.id)">刪除會員</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="userlogModal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>登入登出紀錄</h3>
        </div>
        <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>操作</td>
                        <td>時間</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in userlog">
                        <td>{{index + 1}}</td>
                        <td>{{item.action}}</td>
                        <td>{{item.time}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn">關閉</button>
        </div>
    </div>
    <div id="edituserModal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>編輯會員</h3>
        </div>
        <div class="modal-body">
            <input type="text" class="textbox" placeholder="帳號" v-model="edit.account">
            <input type="text" class="textbox fakepassword" placeholder="密碼" v-model="edit.password">
            <label style="text-align: left">權限:</label>
            <select style="width: 100%;height: 40px" v-model="edit.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="edituser()">儲存</button>
            <button class="btn" data-dismiss="modal" @click="userload()">取消</button>
        </div>
    </div>
    <div id="adduserModal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>編輯會員</h3>
        </div>
        <div class="modal-body">
            <input type="text" class="textbox" placeholder="帳號" v-model="add.account">
            <input type="text" class="textbox fakepassword" placeholder="密碼" v-model="add.password">
            <label style="text-align: left">權限:</label>
            <select style="width: 100%;height: 40px" v-model="add.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="adduser()">儲存</button>
            <button class="btn" data-dismiss="modal">取消</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userid: <?=$_SESSION['userid']?>,
                users: [],
                userlog: [],
                edit:{
                    id:-1,
                    account:"",
                    password:"",
                    groups:""
                },
                add:{},
                formateadd:{
                    account:"",
                    password:"",
                    groups:"user"
                }
            }
        },
        methods:{
            logout(){
                $.get('api.php?do=logout',function (){})
                alert("登出成功")
                location.href = "index.php"
            },
            adduserModal(){
                this.add = this.formateadd
                $('#adduserModal').modal('show')
            },
            adduser(){
                if (this.add.account == "" || this.add.password == "") return alert("帳號密碼不可為空")
                $.post('api.php?do=adduser',this.$data.add,function (){})
                this.userload()
            },
            userload(){
                const _this = this
                $.get('api.php?do=userlist',function (a) {
                    _this.users = JSON.parse(a)
                })
            },
            edituserModal(idx){
                if (this.userid == this.users[idx].id) return alert("請勿嘗試編輯自己")
                this.edit = this.users[idx]
                $("#edituserModal").modal('show')
            },
            edituser(){
                $.post('api.php?do=edituser',this.$data.edit,function () {})
                alert("編輯成功")
                this.userload()
            },
            userlogModal(id){
                const _this = this
                $.post('api.php?do=userlog',{userid:id},function (a) {
                    _this.userlog = JSON.parse(a)
                })
                $("#userlogModal").modal('show')
            },
            deluser(id){
                if (this.userid == id) return alert("請勿嘗試刪除自己")
                $.post('api.php?do=deluser',{userid:id},function (){})
                alert("刪除成功")
                this.userload()
            }
        },
        mounted(){
            this.userload()
        }
    }).mount('#app')
</script>
</body>
</html>