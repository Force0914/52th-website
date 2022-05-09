<!doctype html>
<html lang="en">
<head>
    <?php include ("head.php");?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="center text-center">
    <div class="login">
        <img src="img/logo.png" class="logo">
        <h1>會員網站前台登入模組</h1>
        <input type="text" class="newinput" placeholder="帳號" v-model="account">
        <input type="text" class="newinput fakepassword" placeholder="密碼" v-model="password">
        <input type="button" value="重新產生" class="btn" @click="captcha">
        <input type="button" value="切換驗證碼輸入模式" class="btn" @click="this.switch = !this.switch">
        <img :src=`gd.php?${v}` class="captcha" @click="captcha" draggable="false">
        <p>(點擊圖片重新產生驗證碼)</p>
        <div v-if="this.switch">
            <div class="dragbox" v-show="list1.length >= 1">
                <div class="drag" v-for="(item,index) in list1" @dragstart="drag(index)" draggable="true"><img :src=`gd.php?${item}` draggable="false"></div>
            </div>
            <div class="dragbox" @drop="drop()" @dragenter.prevent @dragover.prevent>
                <div class="drag" v-for="(item,index) in list2"><img :src=`gd.php?${item}` draggable="false"></div>
            </div>
        </div>
        <div v-else>
            <input type="text" class="newinput" placeholder="驗證碼" maxlength="4" v-model="inputcaptcha" @input="input()">
        </div>
        <input type="button" value="登入" class="btn" @click="login()">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                switch: true,
                list1:[],
                list2:[],
                v:"54kj",
                movedata:"",
                account:"",
                password:"",
                inputcaptcha:"",
                err: 0
            }
        },
        methods:{
            captcha(){
                this.inputcaptcha = ""
                this.v = ""
                for (i = 0; i < 4; i++) {
                    switch (rand(1,3)){
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
                this.list1 = this.v.split("").sort(()=>{return Math.random() - 0.8})
                this.list2 = []
                if (this.list1.join("") == this.v) this.resetlist()
            },
            drag(idx){
                this.movedata = idx
            },
            drop(){
                let idx = this.movedata
                this.list2.push(this.list1[idx])
                this.list1.splice(idx,1)
                this.inputcaptcha = this.list2.join("")
            },
            input(){
                this.inputcaptcha.split("").forEach((e)=>{
                    if (this.list1.indexOf(e) >= 0){
                        this.list2.push(this.list1[this.list1.indexOf(e)])
                        this.list1.splice(this.list1.indexOf(e),1);
                    }
                })
            },
            login(){
                const _this = this
                $.post('api.php?do=login',this.$data,function (a){
                    if (a == "admin" || a == "user"){
                        alert("登入成功")
                        location.href = `${a}.php`
                    }else {
                        alert(a)
                        _this.err++
                        if (_this.err >= 3) return location.href = "logerror.php"
                        _this.captcha()
                    }
                })
            }
        },
        mounted(){
            this.captcha()
        }
    }).mount("#app")
    function rand(min,max){
        return (Math.floor(Math.random() * (max-min+1))) + min
    }
    function chr(chr){
        return String.fromCharCode(chr)
    }
</script>
</body>
</html>