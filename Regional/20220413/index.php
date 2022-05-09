<!doctype html>
<html lang="en">
<head>
    <?php include ("head.php");?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="center">
    <div class="login text-center">
        <img src="img/logo.png" class="logo">
        <h1>會員網站前台登入模組</h1>
        <input type="text" placeholder="帳號" class="newinput" v-model="account">
        <input type="text" placeholder="密碼" class="newinput fakepassword" v-model="password">
        <img :src=`gd.php?${v}` style="zoom: 3;" @click="captcha()" draggable="false">
        <p>(點擊圖片更新驗證碼)</p>
        <input type="button" value="重新產生" class="btn" @click="captcha()">
        <input type="button" value="切換驗證碼輸入模式" class="btn" @click="this.captchamode = !this.captchamode">
        <div class="dragbox" v-show="list1.length >= 1 && !captchamode">
            <div class="drag" v-for="(item,index) in list1" draggable="true" @dragstart="drag(index)"><img :src=`gd.php?${item}` draggable="false"></div>
        </div>
        <div class="dragbox" v-show="!captchamode" @drop="drop()" @dragenter.prevent @dragover.prevent>
            <div class="drag" v-for="(item,index) in list2"><img :src=`gd.php?${item}` draggable="false"></div>
        </div>
        <input type="text" v-show="captchamode" class="newinput" placeholder="驗證碼" v-model="captchacode" maxlength="4" @input="input()">
        <input type="button" value="登入" class="btn" @click="login">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                v:"54kj",
                list1:[],
                list2:[],
                movedata:-1,
                account:"",
                password:"",
                err: 0,
                bla: "",
                captchamode: false,
                captchacode:"",
                oldlength: 0
            }
        },
        methods:{
            captcha(){
                this.v = ""
                for (i = 0; i < 4; i++) {
                    switch (rand(1,3)) {
                        case 1:
                            this.v += rand(0,9)
                            break;
                        case 2:
                            this.v += chr(rand(65,90))
                            break;
                        case 3:
                            this.v += chr(rand(97,122))
                            break;
                    }
                }
                this.resetlist()
            },
            resetlist(){
                this.list1 = this.v.split("").sort(()=>{return Math.random()-0.5})
                this.list2 = []
                this.captchacode = ""
                if (this.list1.join("") == this.v) this.resetlist()
            },
            drag(idx){
                this.movedata = idx
            },
            drop(){
                let idx = this.movedata
                this.list2.push(this.list1[idx])
                this.list1.splice(idx,1)
                this.captchacode = this.list2.join("")
            },
            login(){
                const _this = this
                $.post('api.php?do=login',this.$data,function (a){
                    if (a == "admin" || a == "user"){
                        alert("登入成功")
                        location.href = `${a}.php`
                    }else{
                        _this.captcha()
                        _this.err++
                        if (_this.err >= 3) location.href = "logerror.php"
                        alert(a)
                    }
                })
            },
            input(){
                let bla = []
                this.captchacode.split("").forEach((e)=>{
                    if (this.list1.indexOf(e) >= 0){
                        this.list2.push(this.list1[this.list1.indexOf(e)])
                        this.list1.splice(this.list1.indexOf(e),1);
                    }
                    bla.push(this.list2.indexOf(e))
                })
                for (i = 0; i < this.list2.length; i++) {
                    if (bla.indexOf(i) == -1){
                        this.list1.push(this.list2[i])
                        this.list2.splice(i,1);
                    }
                }
            }
        },
        mounted(){
            this.captcha()
        }
    }).mount("#app")

    function rand(min,max) {
        return Math.floor((Math.random() * (max-min+1))) + min
    }

    function chr(chr) {
        return String.fromCharCode(chr)
    }
</script>
</body>
</html>