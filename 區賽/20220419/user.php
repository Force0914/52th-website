<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="text-center">
    <div @mousemove="mousemove" @mouseup="mouseup">
        <img src="img/logo.png" class="logo">
        <h1>{{date}} TODO 工作表</h1>
        <input type="button" value="登出" class="btn logout" @click="logout()">
        <div class="btn-group">
            <input type="button" value="新增工作項目" class="btn" @click="addwork">
            <input type="date" v-model="date" style="margin: 0" @input="blaload()">
            <input type="button" value="設定篩選條件" class="btn" @click="filtermodal">
        </div>
        <div class="block" v-for="(item,index) in works" @mousedown="mousedown(index)" @dblclick="editwork(index)" :style="{'top':197+item.startTime*50+'px','left':200+180*item.location+'px','height':(item.endTime-item.startTime)*50+'px'}">
            <div class="block-padding">
                <div class="block-head">
                    <p>{{item.startTime.toString().padStart(2,"0")}}:00-{{item.endTime.toString().padStart(2,"0")}}:00</p>
                    <p :class="{'badge':true, 'badge-success':item.status == 'c', 'badge-info':item.status == 'b', 'badge-warning':item.status == 'a'}">{{item.status == "a" ? "未處理" : item.status == "b" ? "處理中" : "已完成"}}</p>
                    <button class="close" @click="delwork(item.id)">&times;</button>
                </div>
                <div class="block-body">
                    <p :class="{'badge':true, 'badge-success':item.speed == 'a', 'badge-info':item.speed == 'b', 'badge-warning':item.speed == 'c'}">{{item.speed == "a" ? "普通件" : item.speed == "b" ? "速件" : "最速件"}}</p>
                    <b>{{item.name}}</b>
                </div>
            </div>
        </div>
        <div class="table-border">
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>時間</td>
                    <td><p style="float: left">工作計畫</p><p style="float: right">(雙擊工作項目以編輯)</p></td>
                </tr>
                </thead>
                <tbody class="work">
                <tr v-for="i in time">
                    <td>{{i.toString().padStart(2,"0")}}:00-{{(i+2).toString().padStart(2,"0")}}:00</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="editmodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>工作編輯</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input type="text" class="width15" v-model="edit.name">
            <label>開始時間</label>
            <select class="width80" v-model="edit.startTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) >= edit.endTime">{{(i-1).toString().padStart(2,"0")}}:00</option>
            </select>
            <label>結束時間</label>
            <select class="width80" v-model="edit.endTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) <= edit.startTime">{{(i-1).toString().padStart(2,"0")}}:00</option>
            </select>
            <label>處理狀態</label>
            <select class="width80" v-model="edit.status">
                <option value="a">未處理</option>
                <option value="b">處理中</option>
                <option value="c">已完成</option>
            </select>
            <label>優先順序</label>
            <select class="width80" v-model="edit.speed">
                <option value="a">普通件</option>
                <option value="b">速件</option>
                <option value="c">最速件</option>
            </select>
            <label>工作內容</label>
            <textarea cols="30" rows="10" class="width15" v-model="edit.description"></textarea>
        </div>
        <div class="modal-footer">
            <input type="button" value="儲存" class="btn" data-dismiss="modal" @click="save">
            <input type="button" value="取消" class="btn" data-dismiss="modal" @click="blaload">
        </div>
    </div>
    <div id="filtermodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>篩選條件</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input type="text" class="width15" v-model="filterdata.name">
            <label>開始時間</label>
            <select class="width80" v-model="filterdata.startTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) >= filterdata.endTime">{{(i-1).toString().padStart(2,"0")}}:00</option>
            </select>
            <label>結束時間</label>
            <select class="width80" v-model="filterdata.endTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) <= filterdata.startTime">{{(i-1).toString().padStart(2,"0")}}:00</option>
            </select>
            <label>處理狀態</label>
            <select class="width80" v-model="filterdata.status">
                <option value="all">全部狀態</option>
                <option value="a">未處理</option>
                <option value="b">處理中</option>
                <option value="c">已完成</option>
            </select>
            <label>優先順序</label>
            <select class="width80" v-model="filterdata.speed">
                <option value="all">全部狀態</option>
                <option value="a">普通件</option>
                <option value="b">速件</option>
                <option value="c">最速件</option>
            </select>
            <label>工作內容</label>
            <textarea cols="30" rows="10" class="width15" v-model="filterdata.description"></textarea>
        </div>
        <div class="modal-footer">
            <input type="button" value="儲存" class="btn" data-dismiss="modal" @click="blaload">
            <input type="button" value="取消" class="btn" data-dismiss="modal">
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userid: <?=$_SESSION['userid']?>,
                date: this.formatedate(),
                time: [],
                times: [],
                works:[],
                movedata:false,
                filterdata:{
                    name:"",
                    startTime:0,
                    endTime:24,
                    status:"all",
                    speed:"all",
                    description:""
                },
                edit:{}
            }
        },
        methods:{
            blaload(){
              this.resettime()
              this.workload()
            },
            resettime(){
                this.times = []
                for (i = 0; i < 10; i++) {
                    this.times.push([])
                    for (j = 0; j < 24; j++) {
                        this.times[i].push(false)
                    }
                }
            },
            filtermodal(){
                $("#filtermodal").modal("show")
            },
            workload(){
                const _this = this
                $.post('api.php?do=worklist',{userid:this.userid},function (a) {
                    a = JSON.parse(a)
                    let blabla = []
                    a.forEach((b)=>{
                        if (b.date != _this.date) return
                        for (x in _this.filterdata) {
                            switch (x) {
                                case "startTime":
                                    if (b[x] < _this.filterdata[x]) return;
                                    break
                                case "endTime":
                                    if (b[x] > _this.filterdata[x]) return;
                                    break
                                case "status":
                                    if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") return;
                                    break
                                case "speed":
                                    if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") return;
                                    break
                                default:
                                    if (b[x].indexOf(_this.filterdata[x]) == -1) return;
                                    break
                            }
                        }
                        for (i = 0; i < _this.times.length; i++) {
                            let c = false
                            for (j = b.startTime; j < b.endTime; j++) {
                                if (_this.times[i][j]) c = true
                            }
                            if (c) continue
                            for (j = b.startTime; j < b.endTime; j++) {
                                _this.times[i][j] = true
                            }
                            b.location = i
                            blabla.push(b)
                            break
                        }
                    })
                    _this.works = blabla
                })
            },
            addwork(){
                this.edit = {
                    id:-1,
                    userid: this.userid,
                    date: this.date,
                    name:"",
                    startTime:0,
                    endTime:24,
                    status:"a",
                    speed:"a",
                    description:""
                }
                $("#editmodal").modal("show")
            },
            editwork(idx){
                this.edit = this.works[idx]
                this.edit.userid = this.userid
                this.edit.date = this.date
                $("#editmodal").modal("show")
            },
            save(){
                const _this = this
                if (this.edit.id == -1){
                    $.post("api.php?do=addwork",this.$data.edit,function (){
                        _this.blaload()
                    })
                }else {
                    $.post("api.php?do=editwork",this.$data.edit,function (){
                        _this.blaload()
                    })
                }
            },
            delwork(id){
                const _this = this
                if (!confirm("是否確認要刪除此工作計畫?")) return
                $.post('api.php?do=delwork',{id:id},function (){
                    _this.blaload()
                })
            },
            formatedate(){
                let today = new Date().toLocaleDateString().split("/")
                today.forEach((b,idx)=>{
                    today[idx] = b.toString().padStart(2,"0")
                });
                return today.join("-")
            },
            logout(){
                alert("登出成功")
                $.get('api.php?do=logout',function (){})
                location.href = "index.php"
            },
            sort(){
                this.resettime()
                let blabla = []
                this.works.forEach((b)=>{
                    if (b.date != this.date) return
                    for (i = 0; i < this.times.length; i++) {
                        let c = false
                        for (j = b.startTime; j < b.endTime; j++) {
                            if (this.times[i][j]) c = true
                        }
                        if (c) continue
                        for (j = b.startTime; j < b.endTime; j++) {
                            this.times[i][j] = true
                        }
                        b.location = i
                        blabla.push(b)
                        break
                    }
                })
                this.works = blabla
            },
            mousedown(idx){
                this.movedata = idx
            },
            mousemove(event){
                if (!this.movedata) return
                let idx = this.movedata
                let timelong = this.works[idx].endTime - this.works[idx].startTime
                let newTime = Math.floor((event.pageY-197)/50)
                this.works[idx].startTime = newTime < 0 ? 0 : newTime > 24 - timelong ? 24 - timelong : newTime
                this.works[idx].endTime = this.works[idx].startTime + timelong
                this.sort()
            },
            mouseup(){
                if (!this.movedata) return
                let idx = this.movedata
                this.movedata = false
                $.post('api.php?do=editwork',this.works[idx],function (){})
            }
        },
        mounted(){
            for (i = 0; i < 24; i+=2) {
                this.time.push(i)
            }
            this.blaload()
        }
    }).mount("#app")
</script>
</body>
</html>