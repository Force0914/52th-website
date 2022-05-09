<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
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
                <td>{{item.groups == "admin" ? "管理者" : "使用者"}}</td>
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
            <h3>{{edit.title}}</h3>
        </div>
        <div class="modal-body">
            <input style="width: calc(100% - 15px) !important;" type="text" class="newinput" v-model="edit.account" placeholder="帳號">
            <input style="width: calc(100% - 15px) !important;" type="text" class="newinput fakepassword" v-model="edit.password" placeholder="密碼">
            <label style="text-align: left">權限:</label>
            <select style="width: 100% !important;" v-model="edit.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="save()">儲存</button>
            <button class="btn" data-dismiss="modal">取消</button>
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
                        <td>訊息</td>
                        <td>時間</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in userlogdata">
                        <td>{{index+1}}</td>
                        <td>{{item.action}}</td>
                        <td>{{item.time}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="save()">儲存</button>
            <button class="btn" data-dismiss="modal">取消</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userlogdata:[],
                users:[],
                edit:{},
                formateedit:{
                    title:"會員新增",
                    id: -1,
                    account: "",
                    password:"",
                    groups:"user"
                }
            }
        },
        methods:{
            logout(){
                $.get('api.php?do=logout',function(){})
                location.href = 'index.php'
            },
            adduser(){
                this.edit = {}
                this.edit = this.formateedit
                $('#addmodal').modal('show')
            },
            edituser(idx){
                this.edit = {}
                this.edit = this.users[idx]
                this.edit.title = "會員修改"
                $('#addmodal').modal('show')
            },
            save(){
                for (i = 0; i < this.users.length; i++) {
                    if (this.edit.account == this.users[i].account && this.users[i].id != this.edit.id){
                        alert("該使用者名稱已存在")
                        this.userlist()
                        return
                    }
                }
                if (this.edit.account == "" || this.edit.password == "") {
                    alert("有欄位為空, 無法儲存編輯")
                    this.userlist()
                    return
                }
                if(this.edit.title == "會員新增"){
                    $.post('api.php?do=adduser',this.$data.edit,function (){})
                    alert("新增成功")
                }else{
                    $.post('api.php?do=edituser',this.$data.edit,function (){})
                    alert("編輯成功")
                }
                this.userlist()
            },
            userlist(){
                const _this = this
                $.get('api.php?do=userlist',function (a) {
                    _this.users = JSON.parse(a)
                })
            },
            userlog(id){
                const _this = this
                $.post('api.php?do=userlog', {id:id},function (a){
                    _this.userlogdata = JSON.parse(a)
                })
                $('#userlogmodal').modal('show')
            },
            deluser(id){
                $.post('api.php?do=deluser', {id:id},function (){})
                alert("刪除成功")
                this.userlist()
            }
        },
        mounted(){
            this.userlist()
        }
    }).mount("#app")
</script>
</body>
</html>