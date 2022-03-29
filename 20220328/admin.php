<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
</head>
<body>
<div id="app" class="container text-center">
    <img src="img/logo.png" class="logo">
    <h1>會員網站後台管理模組</h1><br>
    <input type="button" value="登出" class="logout btn" @click="logout()">
    <input type="button" value="新增會員" class="btn" @click="createusermodal()"><br><br>
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
                        <button class="btn dropdown-toggle" data-toggle="dropdown">管理員功能
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a @click="editusermodal(index)">編輯會員</a></li>
                            <li><a @click="userlog(item.id)">登入登出紀錄</a></li>
                            <li><a @click="deluser(item.id)">刪除會員</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="blaModal" class="modal fade hide" tabindex="-1">
        <div class="modal-header">
            <button data-dismiss="modal" class="close">&times;</button>
            <h3>編輯會員</h3>
        </div>
        <div class="modal-body">
            <input type="text" placeholder="帳號" class="newinput" v-model="edit.account"><br>
            <input type="text" placeholder="密碼" class="newinput fakepassword" v-model="edit.password">
            <p>使用者權限：</p>
            <select style="height: 40px;width: calc(100% - 85px);margin: 0" v-model="edit.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="edituser()">儲存</button>
            <button class="btn" data-dismiss="modal">取消</button>
        </div>
    </div>
    <div id="addModal" class="modal fade hide" style="height: 400px" tabindex="-1">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>新增會員</h3>
        </div>
        <div class="modal-body">
            <input type="text" placeholder="帳號" class="newinput" v-model="createdata.account"><br>
            <input type="text" placeholder="密碼" class="newinput fakepassword" v-model="createdata.password">
            <p>使用者權限：</p>
            <select style="height: 40px;width: calc(100% - 85px);margin: 0" v-model="createdata.groups">
                <option value="admin">管理員</option>
                <option value="user">使用者</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="createuser()">新增</button>
            <button class="btn" data-dismiss="modal">取消</button>
        </div>
    </div>
    <div id="userlogModal" class="modal fade hide" tabindex="-1">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>登入登出紀錄</h3>
        </div>
        <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>動作</td>
                        <td>時間</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in userlogdata">
                        <td>{{index + 1}}</td>
                        <td>{{item.status}}</td>
                        <td>{{item.timestmp}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">關閉</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                users: [],
                userlogdata:[],
                userid: <?=$_SESSION['userid']?>,
                createdata:{
                    account: "",
                    password: "",
                    groups: "admin"
                },
                edit:{
                    id: 0,
                    account: "",
                    password: "",
                    groups: "admin"
                }
            }
        },
        methods:{
            logout(){
                $.get("api.php?do=logout",function (){})
                alert("登出成功")
                location.href="index.php"
            },
            userdata(){
                const _this = this
                $.get('api.php?do=userlist',function (e) {
                    _this.users = JSON.parse(e)
                })
            },
            createusermodal(){
                this.createdata['account'] = ""
                this.createdata['password'] = ""
                this.createdata['groups'] = "admin"
                $("#addModal").modal("show")
            },
            createuser(){
                let bla = false
                const _this = this
                this.users.forEach(e=>{
                    if (e.account == _this.createdata.account) bla = true
                })
                if (bla) return alert("使用者已存在")
                if (this.createdata.account != "" && this.createdata.password != "" ){
                    $.post("api.php?do=adduser",this.$data.createdata,function (e) {
                        console.log(e)
                    })
                    alert("創建成功")
                    this.userdata()
                }else {
                    alert("欄位不可為空")
                }
            },
            editusermodal(idx){
                if (this.userid == this.users[idx].id) return alert("無法編輯自己")
                for (e in this.edit) {
                    this.edit[e] = this.users[idx][e]
                }
                $("#blaModal").modal("show")
            },
            edituser(){
                $.post("api.php?do=edituser",this.$data.edit,function (){})
                this.userdata()
                alert("編輯成功")
            },
            deluser(userid){
                if (this.userid == userid) return alert("無法刪除自己")
                $.post("api.php?do=deluser",{userid:userid},function (){})
                this.userdata()
                alert("刪除成功")
            },
            userlog(userid){
                const _this = this
                $.post("api.php?do=userlog", {userid: userid},function (e){
                    _this.userlogdata = JSON.parse(e)
                })
                $("#userlogModal").modal("show")
            }
        },
        mounted(){
            this.userdata()
        }
    }).mount("#app")
</script>
</body>
</html>