<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="center text-center">
    <div class="login">
        <img src="img/logo.png" class="logo">
        <h1>會員網站前台登入模組</h1>
        <input type="text" class="newinput" placeholder="帳號" v-model="account">
        <input type="text" class="newinput fakepassword" placeholder="密碼" v-model="password">
        <img class="captcha" :src=`gd.php?${v}` @click="captcha" draggable="false"><p>(點擊圖片更新驗證碼)</p>
        <input type="button" value="重新產生" class="btn" @click="captcha">
        <input type="button" value="切換驗證碼輸入模式" @click="switchinput()" class="btn">
        <div v-if="this.switch">
            <div class="dragbox" v-show="list1.length >= 1">
                <div class="drag" v-for="(item,index) in list1" @dragstart="dragstart(index)" draggable="true"><img :src=`gd.php?${item}` draggable="false"></div>
            </div>
            <div class="dragbox" @drop="drop()" @dragover.prevent @dragenter.prevent>
                <div class="drag" v-for="(item,index) in list2"><img :src=`gd.php?${item}` draggable="false"></div>
            </div>
        </div>
        <input type="text" placeholder="驗證碼" class="newinput" v-else @input="input()" v-model="inputcaptcha">
        <input type="button" value="登入" class="btn" @click="login">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                v:"54kj",
                list1: [],
                list2:[],
                inputcaptcha:"",
                switch: true,
                movedata: -1,
                err:0,
                account:"",
                password:""
            }
        },
        methods:{
            switchinput(){
                this.switch = !this.switch
                this.resetlist()
                this.inputcaptcha = ""
            },
            captcha(){
                this.v = ""
                for (i = 0; i < 4; i++) {
                    switch (rand(1,3)) {
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
                this.list1 = this.v.split("").sort(()=>{return Math.random()-0.5})
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
            input(){
                this.list2 = this.inputcaptcha.split("")
            },
            login(){
                $.post("api.php?do=login",this.$data,function (a){
                    if (a == "admin" || a == "user"){
                        alert('登入成功')
                        location.href = `${a}.php`
                    }else{
                        alert(a)
                        this.captcha()
                        this.err += 1
                        if (this.err >= 3) location.href = "logerror.php"
                    }
                })
            }
        },
        mounted(){
            this.captcha()
        }
    }).mount("#app")
    function rand(min,max){
        return Math.floor(Math.random()*(max-min+1))+min
    }
    function chr(chrcode){
        return String.fromCharCode(chrcode)
    }
</script>
</body>
</html>