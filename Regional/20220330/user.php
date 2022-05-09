<!doctype html>
<html lang="en">
<head>
    <?php include('head.php');?>
</head>
<body>
    <div id="app" class="text-center">
        <img src="img/logo.png" class="logo">
        <h1>{{date}} TODO工作表</h1>
        <div class="block" v-for="(item, index) in works" @dragstart="dragstart(index)" draggable="true" :style="{'top':217+item.startTime * 50+'px','left':200 + 180 * item.location + 'px','height':(item.endTime-item.startTime)*50+'px'}" @dblclick="editw(index)">
            <div class="block-padding">
                <div class="block-head">
                    <p>{{bla(item.startTime)}}:00-{{bla(item.endTime)}}:00</p>
                    <span :class="{'badge':true,'badge-warning':item.status=='a','badge-info':item.status=='b','badge-success':item.status=='c'}">{{item.status == "a" ? "未處理" : item.status == "b" ? "處理中" : "已完成"}}</span>
                    <button class="close" @click="delwork(item.id)">&times;</button>
                </div>
                <div class="block-body">
                    <span :class="{'badge':true,'badge-warning':item.speed=='a','badge-info':item.speed=='b','badge-success':item.speed=='c'}">{{item.speed == "a" ? "普通件" : item.speed == "b" ? "速件" : "最速件"}}</span>
                    <b>{{item.name}}</b>
                 </div>
            </div>
        </div>
        <div class="btn-group">
            <input type="button" class="btn" value="新增工作" @click="addw()">
            <input type="date" style="margin: 0" v-model="date" @input="blaload()">
            <input type="button" class="btn" value="設定篩選條件" @click="filterwork()">
        </div>
        <div class="table-border">
            <table class="table table-striped work">
                <thead>
                <tr>
                    <td>時間</td>
                    <td>工作計畫</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="i in times" @drop="drop()" @dragover="allowdrop($event)">
                    <td>{{bla(i)}}:00-{{bla(i+2)}}:00</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="editModal" class="modal fade hide">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h3>工作編輯</h3>
            </div>
            <div class="modal-body">
                <label>工作名稱</label>
                <input type="text" v-model="edit.name" class="width80">
                <label>處理狀態</label>
                <select v-model="edit.status" class="width80">
                    <option value="a">未處理</option>
                    <option value="b">處理中</option>
                    <option value="c">已完成</option>
                </select>
                <label>優先順序</label>
                <select v-model="edit.speed" class="width80">
                    <option value="a">普通件</option>
                    <option value="b">速件</option>
                    <option value="c">最速件</option>
                </select>
                <label>開始時間</label>
                <select v-model="edit.startTime" class="width80">
                    <option v-for="i in 25" :value="i-1" :disabled="(i-1) >= edit.endTime">{{bla(i-1)}}:00</option>
                </select>
                <label>結束時間</label>
                <select v-model="edit.endTime" class="width80">
                    <option v-for="i in 25" :value="i-1" :disabled="(i-1) <= edit.startTime">{{bla(i-1)}}:00</option>
                </select>
                <label>工作內容</label>
                <textarea cols="30" rows="10" v-model="edit.description" class="width80"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" @click="save()">確定</button>
                <button class="btn" data-dismiss="modal" @click="blaload()">取消</button>
            </div>
        </div>
        <div id="filterModal" class="modal fade hide">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h3>工作編輯</h3>
            </div>
            <div class="modal-body">
                <label>工作名稱</label>
                <input type="text" v-model="filterdata.name" class="width80">
                <label>處理狀態</label>
                <select v-model="filterdata.status" class="width80">
                    <option value="a">未處理</option>
                    <option value="b">處理中</option>
                    <option value="c">已完成</option>
                </select>
                <label>優先順序</label>
                <select v-model="filterdata.speed" class="width80">
                    <option value="a">普通件</option>
                    <option value="b">速件</option>
                    <option value="c">最速件</option>
                </select>
                <label>開始時間</label>
                <select v-model="filterdata.startTime" class="width80">
                    <option v-for="i in 25" :value="i-1" :disabled="(i-1) >= filterdata.endTime">{{bla(i-1)}}:00</option>
                </select>
                <label>結束時間</label>
                <select v-model="filterdata.endTime" class="width80">
                    <option v-for="i in 25" :value="i-1" :disabled="(i-1) <= filterdata.startTime">{{bla(i-1)}}:00</option>
                </select>
                <label>工作內容</label>
                <textarea cols="30" rows="10" v-model="filterdata.description" class="width80"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" @click="blaload()">確定</button>
                <button class="btn" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
    <script>
        let vue = Vue.createApp({
            data(){
                return{
                    userid: <?=$_SESSION['userid']?>,
                    date: this.formatdate(),
                    times:[],
                    time:[],
                    works:[],
                    movedata: -1,
                    filterdata:{
                        date:"",
                        name:"",
                        speed:"all",
                        status:"all",
                        startTime:0,
                        endTime:24,
                        description:""
                    },
                    edit:{},
                    formatedit:{
                        id: -1,
                        name: "",
                        speed: "a",
                        staus: "a",
                        startTime: 0,
                        endTime: 24,
                        description: ""
                    }
                }
            },
            methods:{
                workload(){
                    const _this = this
                    let bla = []
                    $.post('api.php?do=worklist',{userid:this.userid},function (a){
                        a = JSON.parse(a)
                        a.forEach(b=>{
                            if (b.date != _this.date) return
                            let blabla = false
                            for (x in _this.filterdata) {
                                switch (x){
                                    case "startTime":
                                        if (b[x] < _this.filterdata[x]) blabla = true
                                        break
                                    case "endTime":
                                        if (b[x] > _this.filterdata[x]) blabla = true
                                        break
                                    case "status":
                                        if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") blabla = true
                                        break
                                    case "speed":
                                        if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") blabla = true
                                        break
                                    default:
                                        if (b[x].indexOf(_this.filterdata[x]) == -1) blabla = true
                                        break
                                }
                            }
                            if (blabla) return;
                            for (i = 0; i < _this.time.length; i++) {
                                let c = false
                                for (j = b.startTime; j < b.endTime; j++) {
                                    if (_this.time[i][j]) c = true
                                }
                                if ( _this.time.length == i && c) {
                                    _this.time.push([])
                                    for (j = 0; j < _this.time.length; j++) {
                                        _this.time[_this.time.length].push(false)
                                    }
                                }
                                if (c) continue
                                for (j = b.startTime; j < b.endTime; j++) {
                                    _this.time[i][j] = true
                                }
                                b.location = i
                                bla.push(b)
                                break
                            }
                        })
                        _this.works = bla
                    })
                },
                resettime() {
                  this.time = []
                    for (i = 0; i < 10; i++) {
                        this.time.push([])
                        for (j = 0; j < 24; j++) {
                            this.time[i].push(false)
                        }
                    }
                },
                blaload(){
                    this.resettime()
                    this.workload()
                },
                filterwork(){
                    $('#filterModal').modal('show')
                },
                bla(num){
                    return num <= 9 ? "0" + num : num
                },
                formatdate(){
                    let a = new Date().toLocaleDateString().split("/")
                    a.forEach((e,idx)=>{
                        a[idx] = this.bla(e)
                    })
                    return a.join("-")
                },
                addw(){
                    this.edit = this.formatedit
                    $('#editModal').modal('show')
                },
                editw(idx){
                    this.edit = this.works[idx]
                    $('#editModal').modal('show')
                },
                save(){
                    if (this.edit.id == -1){
                        this.addwork()
                    }else {
                        this.editwork()
                    }
                },
                addwork(){
                    this.edit.userid = this.userid
                    this.edit.date = this.date
                    $.post('api.php?do=addwork',this.$data.edit,function (a){
                        console.log(a)
                    })
                    this.blaload()
                },
                editwork(){
                    $.post('api.php?do=editwork',this.$data.edit,function (){})
                    this.blaload()
                },
                drop(){
                    let idx = this.movedata
                    $.post('api.php?do=editwork',this.$data.works[idx],function (){})
                    this.blaload()
                },
                dragstart(idx){
                    this.movedata = idx
                },
                allowdrop(event){
                    let newTime = Math.floor((event.layerY -217)/50)
                    let idx = this.movedata
                    let timeLong = this.works[idx].endTime - this.works[idx].startTime
                    this.works[idx].startTime = newTime <= 0 ? 0 : newTime + timeLong >= 24 ? 24 - timeLong : newTime
                    this.works[idx].endTime = this.works[idx].startTime + timeLong
                    event.preventDefault()
                },
                delwork(id){
                    if (confirm("是否要確認要刪除此工作?")){
                        $.post('api.php?do=delwork',{id:id},function () {})
                        alert("刪除成功")
                        this.blaload()
                    }
                }
            },
            mounted(){
                for (i = 0; i < 24; i+=2) {
                    this.times.push(i)
                }
                this.blaload()
            }
        }).mount("#app")
    </script>
</body>
</html>