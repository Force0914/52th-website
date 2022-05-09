<!doctype html>
<html lang="en">
<head>
    <?php include ('head.php');?>
</head>
<body>
    <div class="login" id="app">
        <div class="login-block text-center">
            <img src="img/logo.png" class="logo">
            <h2>會員網站前台登入模組</h2>
            <input type="text" class="textbox" placeholder="帳號" v-model="account">
            <input type="text" class="textbox fakepassword" placeholder="密碼" v-model="password">
            <img :src=`gd.php?${v}` style="zoom:300%" @click="captcha()"><p>（點擊圖片重設驗證碼）</p><br>
            <div class="captcha" v-show="list1.length != 0">
                <div class="dragbox" v-for="(item, index) in list1" :key="index" @dragstart="dragstart(index)" draggable="true">{{item}}</div>
            </div>
            <div class="captcha" @drop="drop()" @dragover.prevent @dragenter.prevent>
                <div class="dragbox" v-for="(item, index) in list2">{{item}}</div>
            </div>
            <input type="button" value="登入" class="btn" style="width: 100%" @click="login()">
        </div>
    </div>
    <script>
        let vue = Vue.createApp({
            data(){
                return{
                    account: "",
                    password: "",
                    list1: [],
                    list2 :[],
                    v: "",
                    error: 0,
                    movedata: ""
                }
            },
            methods:{
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
                    this.list1 = this.v.split("").sort(()=>{return Math.random() - 0.5})
                    this.list2 = []
                    if (this.list1.join("") == this.v) this.resetlist()
                },
                login(){
                    const _this = this
                    $.post('api.php?do=login',this.$data,function (a) {
                        if(a == "admin" || a == "user"){
                            alert("登入成功")
                            location.href = `${a}.php`
                        }else{
                            _this.resetlist()
                            _this.error += 1
                            alert(`${a}\n\n登入資訊輸入連續誤錯${_this.error}次`)
                            if(_this.error >= 3){
                                location.href = "logerror.php"
                            }
                        }
                    })
                },
                dragstart(idx) {
                    this.movedata = idx
                },
                drop(){
                    let idx = this.movedata
                    this.list2.push(this.list1[idx])
                    this.list1.splice(idx,1)
                }
            },
            mounted(){
                this.captcha()
            }
        }).mount("#app")
        function rand(min,max){
            return Math.floor((Math.random()*(max-min))+min)
        }
        function chr(chr){
            return String.fromCharCode(chr)
        }
    </script>
</body>
</html>