<!doctype html>
<html lang="en">
<head>
    <?php include ('head.php');?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="text-center">
    <img src="img/logo.png" class="logo">
    <h1>會員網站後台管理模組</h1>
    <input type="button" value="登出" class="btn logout" @click="logout()">
    <input type="button" value="會員新增" class="btn" @click="adduser()">
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
                    <td>{{item.groups == "admin" ? "管理員" : "使用者"}}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                管理者功能
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a @click="edituser(index)">修改</a></li>
                                <li><a @click="userlog(item.id)">登入登出紀錄</a></li>
                                <li><a @click="deluser(item.id)">刪除</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="addmodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>會員新增</h3>
        </div>
        <div class="modal-body">
            <input type="text" class="newinput" v-model="add.account" placeholder="帳號">
            <input type="text" class="newinput fakepassword" v-model="add.password" placeholder="密碼">
            <label>權限</label>
            <select v-model="add.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <input type="button" value="儲存" class="btn" data-dismiss="modal" @click="saveadd()">
            <input type="button" value="取消" class="btn" data-dismiss="modal" @click="userlist()">
        </div>
    </div>
    <div id="editmodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>會員編輯</h3>
        </div>
        <div class="modal-body">
            <input type="text" class="newinput" v-model="edit.account" placeholder="帳號">
            <input type="text" class="newinput fakepassword" v-model="edit.password" placeholder="密碼">
            <label>權限</label>
            <select v-model="edit.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <input type="button" value="儲存" class="btn" data-dismiss="modal" @click="saveedit()">
            <input type="button" value="取消" class="btn" data-dismiss="modal" @click="userlist()">
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
                    <tr v-for="(item,index) in userlogdata">
                        <td>{{index + 1}}</td>
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
                userlogdata:[],
                users:[],
                add:{},
                edit:{},
            }
        },
        methods:{
            userlist(){
                const _this = this
                $.get('api.php?do=userlist',function (a){
                    _this.users = JSON.parse(a)
                })
            },
            userlog(id){
                const _this = this
                $.post('api.php?do=userlog',{id:id},function (a){
                    _this.userlogdata = JSON.parse(a)
                })
                $('#userlogmodal').modal('show')
            },
            adduser(){
                this.add = {
                    account:"",
                    password:"",
                    groups:"user"
                }
                $('#addmodal').modal('show')
            },
            saveadd(){
                let c = false
                const _this = this
                this.users.forEach((e)=>{
                    if (e.account == _this.add.account) c = true
                })
                if (c) return alert("該使用者已存在")
                $.post("api.php?do=adduser",this.$data.add,function (){})
                this.userlist()
            },
            edituser(idx){
                this.edit = this.users[idx]
                $('#editmodal').modal('show')
            },
            saveedit(){
                $.post("api.php?do=edituser",this.$data.edit,function (){})
                this.userlist()
                if (this.edit.id == this.userid && this.edit.groups != 'admin') location.href = "index.php"
            },
            deluser(id){
                if (!confirm("是否確認刪除使用者?")) return
                $.post('api.php?do=deluser',{id:id},function (){})
                alert("刪除成功")
                this.userlist()
                if(id == this.userid) location.href = "index.php"
            },
            logout(){
                $.get('api.php?do=logout',function (){})
                alert("登出成功")
                location.href = "index.php"
            }
        },
        mounted(){
            this.userlist()
        }
    }).mount("#app")
</script>
</body>
</html>