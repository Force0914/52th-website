<!doctype html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
    <script src="js/vue.js"></script>
</head>
<body class="center">
    <div class="login text-center" id="app">
        <img src="img/logo.png" class="logo">
        <h2>會員網站前台登入模組</h2>
        <input type="text" class="newinput" placeholder="帳號" v-model="account">
        <input type="text" class="newinput fakepassword" placeholder="密碼" v-model="password">
        <img :src=`gd.php?${v}` class="captcha" @click="captcha()">
        <p>(點擊圖片更新驗證碼)</p>
        <div class="dragbox" v-show="list1.length > 0">
            <div class="drag" v-for="(item, index) in list1" draggable="true" @dragstart="dragstart(index)">{{item}}</div>
        </div>
        <div class="dragbox" @drop="drop()" @dragenter.prevent @dragover.prevent>
            <div class="drag" v-for="item in list2">{{item}}</div>
        </div>
        <input type="button" class="btn" value="登入" @click="login()">
    </div>
    <script>
        let vue = Vue.createApp({
            data(){
                return{
                    account: "",
                    password: "",
                    list1:[],
                    list2:[],
                    v:"",
                    movedata:-1,
                    error: 0
                }
            },
            methods:{
                captcha(){
                    this.v = ""
                    for (i = 0; i < 4; i++) {
                        switch (rand(0,3)){
                            case 0:
                                this.v += rand(0,9)
                                break
                            case 1:
                                this.v += chr(rand(65,90))
                                break
                            case 2:
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
                    this.list2.push(this.list1[this.movedata])
                    this.list1.splice(this.movedata,1)
                },
                login(){
                    const _this = this
                    $.post('api.php?do=login',this.$data,function (a) {
                        if (a == "admin" || a == "user"){
                            alert("登入成功")
                            location.href = `${a}.php`
                        }else {
                            _this.error += 1
                            if (_this.error >=3) location.href = "logerror.php";
                            alert(a)
                        }
                    })
                }
            },
            mounted(){
                this.captcha()
            }
        }).mount("#app")
        function rand(min,max){
            return Math.floor(Math.random()*(max-min)+min)
        }
        function chr(chr){
            return String.fromCharCode(chr)
        }
    </script>
</body>
</html>