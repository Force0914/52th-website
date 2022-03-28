<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
</head>
<body>
<div class="center" id="app">
    <div class="login">
        <img src="img/logo.png" class="logo">
        <h1>網站會員管理系統</h1>
        <input type="text" placeholder="帳號" v-model="account">
        <input type="text" placeholder="密碼" class="fakepassword" v-model="password">
        <img :src=`gd.php?${v}` class="captcha" @click="captcha()"><p>(點擊圖片更新驗證碼)</p>
        <div class="block" v-show="list1.length != 0">
            <div class="cblock" v-for="(item, index) in list1" :key="index" draggable="true" @dragstart="ondrag($event,index)">{{item}}</div>
        </div>
        <div class="block" @drop="ondrop($event)" @dragenter.prevent @dragover.prevent>
            <div class="cblock" v-for="item in list2">{{item}}</div>
        </div>
        <input class="btn" type="button" value="登入" @click="login">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                v: "",
                list1:[],
                list2:[],
                account:"",
                password:"",
                error: 0
            }
        },
        methods:{
            captcha() {
                this.v = ""
                for (i = 0; i < 4; i++) {
                    switch (rand(1,3)) {
                        case 1:
                            this.v += rand(1,9)
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
            resetlist() {
                this.list1 = this.v.split("").sort(()=>Math.random()-0.5)
                this.list2 = []
                this.list1.join() === this.v && this.resetlist()
            },
            ondrag(event, idx){
                event.dataTransfer.setData("idx",idx)
            },
            ondrop(event){
                let idx = event.dataTransfer.getData("idx")
                this.list2.push(this.list1[idx])
                this.list1.splice(idx, 1)
            },
            err(){
                this.error += 1
                if (this.error >= 3) location.href = "logerror.php"
            },
            login(){
                const _this = this
                $.post('api.php?do=login',this.$data,function (e){
                    if (e == "admin" || e == "user"){
                        alert("登入成功")
                        location.href = `${e}.php`
                    }else{
                        alert(e)
                        _this.err()
                    }
                })
            }
        },
        mounted(){
            this.captcha()
        }
    }).mount('#app')
    function rand(min,max) {
        return Math.floor(Math.random()*(max-min+1))+min
    }
    function chr(chr){
        return String.fromCharCode(chr);
    }
</script>
</body>
</html>