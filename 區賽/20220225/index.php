<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('header.php');?>
    <title>使用者登入驗證頁面</title>
</head>
<body>
<div id="app" class="center">
    <div class="login">
        <img src="img/logo.png" style="height: 30px;display: flex;margin: 0 auto" class="text-center" alt="">
        <h1 class="text-center">網站會員管理系統</h1>
        <input type="text" placeholder="帳號" v-model="account"><br>
        <input class="pass" type="text" placeholder="密碼" v-model="password"><br>
        <p>驗證碼</p><img :src=`gd.php?${this.v}` alt="captcha" @click="captcha()"><span>（點擊圖片更新驗證碼）</span><br>
        <div class="drogbox" v-show="list1.length > 0">
            <div class="drog" v-for="(item, index) in list1" :key="index" draggable="true" @dragstart="onStartDrag(index)">
                {{ item }}
            </div>
        </div>
        <div class="drogbox border" @drop="onDrop" @dragover.prevent @dragenter.prevent>
            <span v-if="list1.length == 4">將驗證碼依序拖移至此</span>
            <div class="drog" v-for="(item, index) in list2" :key="index">
                {{ item }}
            </div>
        </div>
        <input style="width: 100%" type="button" class="btn" value="登入" @click="login">
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data() {
            return {
                list1:[],
                list2:[],
                v:"",
                bla:"",
                account:"",
                password:"",
                logerror:0
            }
        },
        methods:{
            login(){
                const _this = this
                $.post(`api.php?do=login`,this.$data,function (a){
                    if(a == "admin" || a == "user"){
                        alert("登入成功")
                        location.href = `${a}.php`
                    }else {
                        _this.err()
                        _this.captcha()
                        alert(a);
                    }
                })
            },
            captcha(){
                let v = "";
                this.list1 = [];
                for (i=0;i<=3;i++){
                    switch (rand(1,3)) {
                        case 1:
                            v += rand(0,9);
                            break;
                        case 2:
                            v += chr(rand(65,90));
                            break;
                        case 3:
                            v += chr(rand(97,122));
                            break;
                    }
                }
                this.v = v
                this.resetlist()
            },
            resetlist(){
                this.list1 = this.v.split("").sort(() =>Math.random() - 0.5)
                this.list2 = []
                if (this.list1.join("") == this.v) this.resetlist()
            },
            onStartDrag(index){
                this.bla = index
            },
            onDrop(){
                let index = this.bla
                if (this.list1[index] == undefined) return
                this.list2.push(this.list1[index])
                this.list1.splice(index, 1)
            },
            err(){
                this.logerror += 1
                if (this.logerror >= 3) location.href = "logerror.php"
            }
        },
        mounted(){
            this.captcha()
        }
    }).mount('#app')
    function rand(min,max){
        return Math.floor(Math.random()*(max-min+1))+min;
    }
    function chr(ascii){
        return String.fromCharCode(ascii)
    }
</script>
</body>
</html>