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
    <input type="button" class="btn" value="會員新增" @click="adduser()" style="margin: 10px 0">
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
                        <div class='btn-group'>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                管理員功能
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a @click="edituser(index)">修改</a></li>
                                <li><a @click="userlogModal(item.id)">登入登出紀錄</a></li>
                                <li><a @click="deluser(item.id)">刪除</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="edituserModal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>會員編輯</h3>
        </div>
        <div class="modal-body">
            <input type="text" class="newinput" style="width: 80%" placeholder="帳號" v-model="edit.account">
            <input type="text" class="newinput fakepassword" style="width: 80%" placeholder="密碼" v-model="edit.password">
            <label>權限修改</label>
            <select style="width: 80%" v-model="edit.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="save()">儲存</button>
            <button class="btn" data-dismiss="modal" @click="userlist()">取消</button>
        </div>
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
                    <tr v-for="(item,index) in userlog">
                        <td>{{index + 1}}</td>
                        <td>{{item.action}}</td>
                        <td>{{item.time}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="save()">儲存</button>
            <button class="btn" data-dismiss="modal" @click="userlist()">取消</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                users:[],
                userlog:[],
                edit:{},
                formateedit:{
                    id:-1,
                    account:"",
                    password:"",
                    groups:"user"
                }
            }
        },
        methods:{
            userlist(){
                const _this = this
                $.get('api.php?do=userlist',function (a){
                    _this.users = JSON.parse(a)
                })
            },
            userlogModal(id){
                const _this = this
                $.post('api.php?do=userlog',{userid:id},function (a){
                    _this.userlog = JSON.parse(a)
                })
                $('#userlogModal').modal('show')
            },
            adduser(){
                this.edit = this.formateedit
                $('#edituserModal').modal("show")
            },
            edituser(idx){
                this.edit = this.users[idx]
                $('#edituserModal').modal("show")
            },
            save(){
                if (this.edit.id == -1){
                    const _this = this
                    let a = false
                    this.users.forEach((b,idx)=>{
                        if (b.account == _this.edit.account) a = true
                    })
                    if (a) return alert("該使用者已存在")
                    $.post('api.php?do=adduser',this.$data.edit,{})
                    alert("新增成功")
                    this.userlist()
                }else {
                    $.post('api.php?do=edituser',this.$data.edit,{})
                    alert("編輯成功")
                    this.userlist()
                }
            },
            deluser(id){
                const _this = this
                if (confirm("是否確認刪除使用者?")){
                    $.post('api.php?do=deluser',{userid:id},function (){})
                    alert("刪除成功")
                    _this.userlist()
                }
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