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
    <input type="button" value="會員新增" class="btn" @click="adduser()">
    <input type="button" value="登出" class="btn logout" @click="logout()">
    <div style="position: absolute;top: 100.5px;right: 5%">
        <p>關鍵字查詢：</p>
        <input type="text" style="margin: 0" v-model="filterdata" @input="filter()">
    </div>
    <div class="table-border">
        <table class="table">
            <thead>
                <tr>
                    <td>#</td>
                    <td @click="userlist('name')">姓名 <img :src="this.sort.name == 0 ? 'img/asc.png' : 'img/desc.png'" v-if="this.sort.name != 1"></td>
                    <td @click="userlist('account')">帳號 <img :src="this.sort.account == 0 ? 'img/asc.png' : 'img/desc.png'" v-if="this.sort.account != 1"></td>
                    <td @click="userlist('id')">使用者編號 <img :src="this.sort.id == 0 ? 'img/asc.png' : 'img/desc.png'" v-if="this.sort.id != 1"></td>
                    <td>權限</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in users">
                    <td>{{index + 1}}</td>
                    <td>{{item.name}}</td>
                    <td>{{item.account}}</td>
                    <td>{{(item.id - 1).toString().padStart(4,"0")}}</td>
                    <td>{{item.groups == "admin" ? "管理員" : "使用者"}}</td>
                    <td><div class="btn-group">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                管理者功能
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="item.id != this.userid && item.account != 'admin'"><a @click="edituser(index)">修改</a></li>
                                <li><a @click="userlog(item.id)">登入登出紀錄</a></li>
                                <li v-if="item.id != this.userid && item.account != 'admin'"><a @click="deluser(item.id)">刪除</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="usermodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>會員編輯</h3>
        </div>
        <div class="modal-body">
            <input type="text" class="newinput" placeholder="姓名" v-model="edit.name">
            <input type="text" class="newinput" placeholder="帳號" v-model="edit.account">
            <input type="text" class="newinput fakepassword" placeholder="密碼" v-model="edit.password">
            <p>權限：</p>
            <select v-model="edit.groups" style="margin: 0">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <input type="button" value="儲存" class="btn" data-dismiss="modal" @click="save()">
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
                    <tr v-for="(item, index) in logdata">
                        <td>{{index+1}}</td>
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
                userid: <?=$_SESSION['userid']?>,
                users:[],
                sort:{
                    id: 0,
                    name:1,
                    account:1
                },
                saveuser:[],
                filterdata:"",
                edit:{},
                logdata: {}
            }
        },
        methods:{
            userlist(sortby){
                const _this = this
                let bruh = "ASC"
                if (sortby == undefined) {
                    sortby = "id";
                    this.sort[sortby] = 0
                }
                for (x in this.sort) {
                    switch (x){
                        case sortby:
                            this.sort[x] = this.sort[x] == 0 ? 2 : 0
                            break
                        default:
                            this.sort[x] = 1
                            break
                    }
                }
                if (this.sort[sortby] == 0) bruh = "DESC"
                $.post('api.php?do=userlist',{sortby:sortby,bruh:bruh},function (a){
                    _this.users = JSON.parse(a)
                    _this.saveuser = JSON.parse(a)
                })
            },
            filter(){
                let a = this.saveuser
                let bla = []
                a.forEach((e)=>{
                    if(e.name.indexOf(this.filterdata) >= 0 || e.account.indexOf(this.filterdata) >= 0) bla.push(e)
                })
                this.users = bla
            },
            adduser(){
                this.edit = {
                    id:-1,
                    name:"",
                    account:"",
                    password:"",
                    groups:"user"
                }
                $("#usermodal").modal("show")
            },
            edituser(idx){
                this.edit = this.users[idx]
                $("#usermodal").modal("show")
            },
            save(){
                const _this = this
                let c = false
                this.saveuser.forEach((e)=>{
                    if (e.account == _this.edit.account && e.id != _this.edit.id) c = true
                })
                if (c) return alert("該使用者已存在")
                if (this.edit.id == -1){
                    $.post('api.php?do=adduser',this.$data.edit,function (){
                        _this.userlist()
                    })
                }else {
                    $.post('api.php?do=edituser',this.$data.edit,function (){
                        _this.userlist()
                    })
                }
            },
            deluser(id){
                const _this = this
                if (!confirm("是否確認刪除使用者?")) return
                $.post('api.php?do=deluser',{id:id},function (){
                    _this.userlist()
                })
            },
            userlog(id){
                const _this = this
                $.post('api.php?do=userlog',{id:id},function (a){
                    _this.logdata = JSON.parse(a)
                })
                $("#userlogmodal").modal("show")
            },
            logout(){
                $.get('api.php?do=logout',function (){})
                alert("登出成功")
                location.href = 'index.php'
            }
        },
        mounted(){
            this.userlist()
        }
    }).mount("#app")
</script>
</body>
</html>