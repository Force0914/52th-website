<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="text-center">
    <img src="img/logo.png" class="logo">
    <h1>{{date}} TODO 工作表</h1>
    <input type="button" value="登出" class="btn logout">
    <div class="block" draggable="true" v-for="(item,index) in works" @dragstart="dragstart(index)" @dblclick="editwork(index)" :style="{'left': 200 + item.location * 180 + 'px','top':207 + item.startTime * 50 + 'px','height':(item.endTime - item.startTime) * 50 + 'px'}">
        <div class="block-padding">
            <div class="block-head">
                <p>{{bla(item.startTime)}}:00-{{bla(item.endTime)}}:00</p>
                <span :class="{'badge':true,'badge-warning':item.status == 'a','badge-info':item.status == 'b','badge-success':item.status == 'c'}">{{item.status == "a" ? "未處理" : item.status == "b" ? "處理中" : "已完成"}}</span>
                <button class="close" @click="delwork(item.id)">&times;</button>
            </div>
            <div class="block-body" style="text-align: left;">
                <span :class="{'badge':true,'badge-warning':item.speed == 'a','badge-info':item.speed == 'b','badge-success':item.speed == 'c'}">{{item.speed == "a" ? "普通件" : item.speed == "b" ? "速件" : "最速件"}}</span>
                <b>{{item.name}}</b>
            </div>
        </div>
    </div>
    <div class="btn-group">
        <input type="button" value="新增工作" class="btn" @click="addwork()">
        <input type="date" style="margin:0" v-model="date" @input="blaload()">
        <input type="button" value="設定篩選條件" class="btn" @click="filterwork()">
    </div>
    <div class="table-border">
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>時間</td>
                    <td>工作內容</td>
                </tr>
            </thead>
            <tbody class="work">
                <tr v-for="i in time" @drop="drop()" @dragover="allowdrag($event)" @dragenter.prevent>
                    <td>{{bla(i)}}:00-{{bla(i+2)}}:00</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="editmodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times</button>
            <h3>工作編輯</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input type="text" class="width80" v-model="edit.name">
            <label>開始時間</label>
            <select class="width80" v-model="edit.startTime">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 >= edit.endTime">{{bla(i-1)}}:00</option>
            </select>
            <label>結束時間</label>
            <select class="width80" v-model="edit.endTime">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 <= edit.startTime">{{bla(i-1)}}:00</option>
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
            <textarea cols="30" rows="10" class="width80" v-model="edit.description"></textarea>
        </div>
        <div class="modal-footer">
            <input type="button" value="儲存" class="btn" @click="save()" data-dismiss="modal">
            <input type="button" value="取消" class="btn" @click="blaload()" data-dismiss="modal">
        </div>
    </div>
    <div id="filtermodal" class="modal fade hide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times</button>
            <h3>工作篩選</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input type="text" class="width80" v-model="filterdata.name">
            <label>開始時間</label>
            <select class="width80" v-model="filterdata.startTime">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 >= filterdata.endTime">{{bla(i-1)}}:00</option>
            </select>
            <label>結束時間</label>
            <select class="width80" v-model="filterdata.endTime">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 <= filterdata.startTime">{{bla(i-1)}}:00</option>
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
            <textarea cols="30" rows="10" class="width80" v-model="filterdata.description"></textarea>
        </div>
        <div class="modal-footer">
            <input type="button" value="儲存" class="btn" @click="blaload()" data-dismiss="modal">
            <input type="button" value="取消" class="btn" data-dismiss="modal">
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userid: <?=$_SESSION['userid']?>,
                date: this.foramtedate(),
                time:[],
                times:[],
                works:[],
                edit:{},
                movedata: -1,
                formateedit:{
                    id: -1,
                    name: "",
                    date:"",
                    startTime : 0,
                    endTime: 24,
                    speed: "a",
                    status: "a",
                    description: ""
                },
                filterdata:{
                    name: "",
                    date:"",
                    startTime : 0,
                    endTime: 24,
                    speed: "all",
                    status: "all",
                    description: ""
                }
            }
        },
        methods:{
            workload(){
                const _this = this
                $.post('api.php?do=worklist',{userid: this.userid},function (a) {
                    let blabla = []
                    a = JSON.parse(a)
                    a.forEach((b,idx)=>{
                        if (b.date != _this.date) return
                        for (x in _this.filterdata) {
                            switch (x) {
                                case "startTime":
                                    if (b[x] < _this.filterdata[x]) return
                                    break
                                case "endTime":
                                    if (b[x] > _this.filterdata[x]) return
                                    break
                                case "status":
                                    if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") return
                                    break
                                case "speed":
                                    if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") return
                                    break
                                default:
                                    if (b[x].indexOf(_this.filterdata[x]) == -1) return
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
                            break
                        }
                        blabla.push(b)
                    })
                    _this.works = blabla
                })
            },
            resettime(){
                this.times = []
                for (i = 0; i < 50; i++) {
                    this.times.push([])
                    for (j = 0; j < 24; j++) {
                        this.times[i].push(false)
                    }
                }
            },
            foramtedate(){
                const  _this = this
                let today = new Date().toLocaleDateString().split("/")
                today.forEach((item,index)=>{
                    today[index] = _this.bla(item)
                })
                return today.join("-")
            },
            bla(num){
                return num <= 9 ? "0" + num : num
            },
            blaload(){
                this.resettime()
                this.workload()
            },
            addwork(){
                this.edit = {}
                this.edit = this.filterdata
                $('#editmodal').modal('show')
            },
            editwork(idx){
                this.edit = {}
                this.edit = this.works[idx]
                $('#editmodal').modal('show')
            },
            filterwork(){
                $('#filtermodal').modal('show')
            },
            save(){
                if (this.edit.id == -1){
                    $.post('api.php?do=addwork',this.$data.edit,function () {})
                    alert('新增成功')
                }else{
                    $.post('api.php?do=editwork',this.$data.edit,function () {})
                    alert('編輯成功')
                }
                this.blaload()
            },
            delwork(id){
                if (confirm("是否確認要刪除此工作項目")){
                    $.post('api.php?do=delwork',{id: id},function () {})
                    this.blaload()
                }
            },
            dragstart(idx){
                this.movedata = idx
            },
            allowdrag(event){
                let idx = this.movedata
                let newTime = Math.floor((event.layerY-207)/50)
                let timelong = this.works[idx].endTime - this.works[idx].startTime
                this.works[idx].startTime = newTime <= 0 ? 0 : newTime >= 24 - timelong ? 24 - timelong : newTime
                this.works[idx].endTime = this.works[idx].startTime + timelong
                event.preventDefault()
            },
            drop(){
                $.post('api.php?do=editwork',this.$data.works[this.movedata],function () {})
                this.blaload()
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