<?php
session_start();
if (!isset($_SESSION["userid"])){
    header("Location:index.php");
}
if ($_SESSION["groups"] != "admin"){
    header("Location:index.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php include("header.php");?>
    <title>會員網站後台管理模組</title>
</head>
<body>
<div class="container" id="app">
    <h1 class="center-title">會員網站後台管理模組</h1>
    <input type="button" value="登出" class="logout btn" @click="logout">
    <table class="table">
        <thead>
        <tr>
            <td>#</td>
            <td>帳號</td>
            <td>權限</td>
            <td><input type="button" value="新增會員" class="btn" @click="createuser"></td>
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
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">會員修改</a>
                            <ul class="dropdown-menu">
                                <li><a tabindex="-1" href="#" @click="changename(item.id)">更改帳號名稱</a></li>
                                <li><a tabindex="-1" href="#" @click="changepassword(item.id)">更改密碼</a></li>
                            </ul>
                        </li>
                        <li><a href="#" @click="showlog(item.id)">登入登出紀錄</a></li>
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">權限修改</a>
                                <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="#" @click="changegroups(item.id,item.groups == `admin` ? `user` : `admin`)">設為{{item.groups != "admin" ? "管理員" : "使用者"}}</a></li>
                                </ul>
                            </li>
                        <li><a href="#" @click="deluser(item.id)">會員刪除</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: block;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">登入登出紀錄</h3>
        </div>
        <div class="modal-body">
            <table class="table">
                <thead>
                <tr>
                    <td>動作</td>
                    <td>系統訊息</td>
                    <td>時間</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item) in userlog">
                    <td v-if="item.userid == this.show">{{item.action == "login" ? "登入" : item.action == "logout" ? "登出" : "登入失敗"}}</td>
                    <td v-if="item.userid == this.show">{{item.message}}</td>
                    <td v-if="item.userid == this.show">{{item.time}}</td>
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
        data() {
            return {
                users: [],
                userlog: [],
                show: 1,
                userid: <?=$_SESSION["userid"]?>
            }
        },
        methods:{
            changename(userid){
                const _this = this
                let set = prompt("請輸入新的帳號名稱")
                if (set == null ) return
                $.post("api.php?do=changename",{userid,set},function () {
                    alert("已成功更改使用者帳號名稱")
                    _this.updatedata()
                })
            },
            changepassword(userid){
                const _this = this
                let set = prompt("請輸入新的密碼")
                if (set == null) return
                $.post("api.php?do=changepassword",{userid,set},function () {
                    alert("已成功更改使用者密碼")
                    _this.updatedata()
                })
            },
            changegroups(userid,set){
                const _this = this
                $.post("api.php?do=changegroups",{userid,set},function () {
                    alert("已成功更改使用者權限")
                    _this.updatedata()
                })
            },
            deluser(userid){
                if (userid == this.userid) {
                    alert("無法刪除自己的使用者帳號")
                    return;
                }
                const _this = this
                if (!confirm("確認刪除使用者？")) return
                $.post("api.php?do=deluser",{userid},function () {
                    alert("已成功刪除使用者")
                    _this.updatedata()
                })
            },
            createuser(){
                const _this = this
                let account = prompt("請輸入使用者的帳號名稱")
                if (account == null){
                    alert("帳號不可為空")
                    return
                }
                let password = prompt("請輸入使用者的密碼")
                if (password == null){
                    alert("密碼不可為空")
                    return
                }
                $.post("api.php?do=createuser",{account,password},function () {
                    alert("創建使用者成功，預設階級:使用者")
                    _this.updatedata()
                })
            },
            showlog(userid){
                this.show = userid
                $('#myModal').modal('show')
            },
            updatedata(){
                const _this = this
                $.get(`api.php?do=userlist`,function (a){
                    _this.users = JSON.parse(a)
                })
            },
            updatelog(){
                const _this = this
                $.get(`api.php?do=userlog`,function (a){
                    _this.userlog = JSON.parse(a)
                })
            },
            logout(){
                $.get("api.php?do=logout",function (){})
                alert("登出成功");
                location.href="index.php"
            }
        },
        mounted(){
            $("#myModal").modal("toggle")
            $("#myModal").modal("toggle")
            this.updatedata()
            this.updatelog()
        }
    }).mount('#app')
</script>
</body>
</html>