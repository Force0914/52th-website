<!doctype html>
<html lang="en">
<head>
    <?php include ('head.php')?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="login">
    <div class="login-block text-center">
        <img src="img/logo.png" class="logo">
        <h2>會員網站前台登入模組</h2>
        <input type="text" class="newinput" placeholder="帳號" v-model="account">
        <input type="text" class="newinput fakepassword" placeholder="密碼" v-model="password">
        <img :src=`gd.php?${v}` style="zoom: 300%" @click="captcha()">
        <p>(點擊圖片更新驗證碼)</p>
        <div class="dragblock" v-show="list1.length != 0">
            <div class="drag" v-for="(item, index) in list1" @dragstart="dragstart(index)" draggable="true">{{item}}</div>
        </div>
        <div class="dragblock" @drop="drop()" @dragenter.prevent @dragover.prevent>
            <div class="drag" v-for="(item, index) in list2">{{item}}</div>
        </div>
        <input type="button" class="btn" value="登入" @click="login()">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                list1:[],
                list2:[],
                v:"1234",
                account:"",
                password:"",
                movedata:"",
                err:0
            }
        },
        methods:{
            captcha(){
                this.v = ""
                for (i = 0; i < 4; i++) {
                    switch (rand(1,3)){
                        case 1:
                            this.v += rand(0,9)
                            break
                        case 2:
                            this.v += chr(rand(65,90))
                            break
                        case 3:
                            this.v += chr(rand(97,122))
                            break
                    }
                }
                this.resetlist()
            },
            resetlist(){
                this.list1 = this.v.split("").sort(()=>{return Math.random() - 0.5})
                this.list2 = []
                if (this.list1.join("") == this.v) this.resetlist()
            },
            dragstart(idx){
                this.movedata = idx
            },
            drop(){
                let idx = this.movedata
                this.list2.push(this.list1[idx])
                this.list1.splice(idx,1)
            },
            login(){
                const _this = this
                $.post('api.php?do=login',this.$data,function (a){
                    if (a == "admin" || a == "user"){
                        alert("登入成功")
                        location.href=`${a}.php`
                    }else{
                        alert(a)
                        _this.err += 1
                        if (_this.err >= 3) location.href = "logerror.php"
                        _this.captcha()
                    }
                })
            }
        },
        mounted(){
            this.captcha()
        }
    }).mount("#app")
    function rand(min,max) {
        return Math.floor(Math.random()*(max-min))+min
    }
    function chr(chr){
        return String.fromCharCode(chr)
    }
</script>
</body>
</html>