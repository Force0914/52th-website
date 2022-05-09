<!doctype html>
<html lang="en">
<head>
    <?php include ('head.php');?>
    <script src="js/vue.js"></script>
</head>
<body>
<div id="app" class="text-center">
    <img src="img/logo.png" class="logo">
    <h1>{{date}} TODO 工作表</h1>
    <input type="button" value="登出" class="btn logout" @click="logout()">
    <div class="block" v-for="(item, index) in works" :style="{'top': 207 + item.startTime*50 +'px','left': 190 + item.location*180 +'px','height':(item.endTime - item.startTime) * 50 + 'px'}" @dblclick="editwork(index)" @dragstart="dragstart(index)" draggable="true">
        <div>
            <div class="block-head">
                <p>{{bla(item.startTime)}}:00-{{bla(item.endTime)}}:00</p>
                <span :class="{'badge':true,'badge-info':item.status == 'a','badge-success':item.status == 'b','badge-warning':item.status == 'c'}">{{item.status == "a" ? "未處理" : item.status == "b" ? "處理中" : "已完成"}}</span>
                <button class="close" @click="delwork(item.id)">&times;</button>
            </div>
            <div class="block-body">
                <span :class="{'badge':true,'badge-info':item.speed == 'a','badge-success':item.speed == 'b','badge-warning':item.speed == 'c'}">{{item.speed == "a" ? "普通件" : item.speed == "b" ? "速件" : "最速件"}}</span>
                <b>{{bla(item.name)}}</b>
            </div>
        </div>
    </div>
    <div class="btn-groups" style="margin: 10px 0">
        <input type="button" class="btn" value="新增工作" @click="addwork()">
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
                <tr v-for="i in time" @drop="drop()" @dragover="allowdrag($event)" @dragenter.prevent>
                    <td>{{bla(i)}}:00-{{bla(i+2)}}:00</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="editworkModal" class="modal fade fide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>工作編輯</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input type="text" v-model="edit.name" style="width: calc(80% - 15px)">
            <label>處理狀態</label>
            <select v-model="edit.status" style="width: 80%">
                <option value="a">未處理</option>
                <option value="b">處理中</option>
                <option value="c">已完成</option>
            </select>
            <label>優先順序</label>
            <select v-model="edit.speed" style="width: 80%">
                <option value="a">普通件</option>
                <option value="b">速件</option>
                <option value="c">最速件</option>
            </select>
            <label>開始時間</label>
            <select v-model="edit.startTime" style="width: 80%">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 >= edit.endTime">{{bla(i-1)}}:00</option>
            </select>
            <label>結束時間</label>
            <select v-model="edit.endTime" style="width: 80%">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 <= edit.startTime">{{bla(i-1)}}:00</option>
            </select>
            <label>工作內容</label>
            <textarea cols="30" rows="10" v-model="edit.description" style="width: 80%;min-width: 80%;max-width: 80%"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="save()">儲存</button>
            <button class="btn" data-dismiss="modal" @click="blaload()">取消</button>
        </div>
    </div>
    <div id="filterworkModal" class="modal fade fide">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>工作編輯</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input type="text" v-model="filterdata.name" style="width: calc(80% - 15px)">
            <label>處理狀態</label>
            <select v-model="filterdata.status" style="width: 80%">
                <option value="all">全部狀態</option>
                <option value="a">未處理</option>
                <option value="b">處理中</option>
                <option value="c">已完成</option>
            </select>
            <label>優先順序</label>
            <select v-model="filterdata.speed" style="width: 80%">
                <option value="all">全部狀態</option>
                <option value="a">普通件</option>
                <option value="b">速件</option>
                <option value="c">最速件</option>
            </select>
            <label>開始時間</label>
            <select v-model="filterdata.startTime" style="width: 80%">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 >= filterdata.endTime">{{bla(i-1)}}:00</option>
            </select>
            <label>結束時間</label>
            <select v-model="filterdata.endTime" style="width: 80%">
                <option v-for="i in 25" :value="i-1" :disabled="i-1 <= filterdata.startTime">{{bla(i-1)}}:00</option>
            </select>
            <label>工作內容</label>
            <textarea cols="30" rows="10" v-model="filterdata.description" style="width: 80%;min-width: 80%;max-width: 80%"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="blaload()">儲存</button>
            <button class="btn" data-dismiss="modal">取消</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userid:<?=$_SESSION['userid']?>,
                time:[],
                times:[],
                works:[],
                date: this.formatedate(),
                filterdata:{
                    name:"",
                    startTime:0,
                    endTime:24,
                    speed: "all",
                    status: "all",
                    description: ""
                },
                edit:{},
                formateedit:{
                    name:"",
                    startTime:0,
                    endTime:24,
                    speed: "a",
                    status: "a",
                    description: ""
                },
                movedata: -1
            }
        },
        methods:{
            blaload(){
              this.resetlist()
              this.workload()
            },

            filterwork(){
                $('#filterworkModal').modal("toggle")
            },
            delwork(id){
                $.post('api.php?do=delwork', {id:id},function (){})
                alert("刪除成功")
                this.blaload()
            },
            addwork(){
                const format = this.formateedit
              this.edit = format
              this.edit.userid = this.userid
                $('#editworkModal').modal("show")
            },
            editwork(idx){
                this.edit = this.works[idx]
                this.edit.userid = undefined
                    $('#editworkModal').modal("show")
            },
            save(){
              if (this.edit.userid != undefined){
                  this.edit.date = this.date
                  $.post('api.php?do=addwork',this.$data.edit,function (){})
                  alert("新增成功")
              }  else {
                  $.post('api.php?do=editwork',this.$data.edit,function (){})
                  alert("編輯成功")
              }
                this.blaload()
            },
            dragstart(idx){
                this.movedata = idx
            },
            allowdrag(event){
                let idx = this.movedata
                let timelong = this.works[idx].endTime - this.works[idx].startTime
                let newTime = Math.floor((event.layerY - 207) / 50)
                this.works[idx].startTime = newTime <= 0 ? 0 : newTime >= 24-timelong ? 24-timelong : newTime
                this.works[idx].endTime = this.works[idx].startTime + timelong
                event.preventDefault()
            },
            drop(){
                $.post('api.php?do=editwork',this.$data.works[this.movedata],function (){})
                this.blaload()
            },
            resetlist(){
                this.times = []
                for (i = 0; i < 10; i++) {
                    this.times.push([])
                    for (j = 0; j < 24; j++) {
                        this.times[i].push(false)
                    }
                }
            },
            workload(){
                const _this = this
                let b = []
                $.post('api.php?do=worklist',this.$data,function (a){
                    a = JSON.parse(a)
                    a.forEach((e,idx)=>{
                        if (e.date != _this.date) return
                        let bruh = false
                        for (x in _this.filterdata) {
                            switch (x){
                                case "startTime":
                                    if (e[x] < _this.filterdata[x]) bruh = true
                                    break
                                case "endTime":
                                    if (e[x] > _this.filterdata[x]) bruh = true
                                    break
                                case "speed":
                                    if (e[x] != _this.filterdata[x] && _this.filterdata[x] != "all") bruh = true
                                    break
                                case "status":
                                    if (e[x] != _this.filterdata[x] && _this.filterdata[x] != "all") bruh = true
                                    break
                                default:
                                    if (e[x].indexOf(_this.filterdata[x]) == -1) bruh = true
                                    break
                            }
                        }
                        if (bruh) return;
                        for (i = 0; i < _this.times.length; i++) {
                            let c = false
                            for (j = e.startTime; j < e.endTime ; j++) {
                                if (_this.times[i][j]) c = true
                            }
                            if (_this.times.length-1 == i && c){
                                _this.times.push([])
                                for (j = 0; j < 24; j++) {
                                    _this.times[_this.times.length].push(false)
                                }
                            }
                            if (c) continue
                            for (j = e.startTime; j < e.endTime; j++) {
                                _this.times[i][j] = true
                            }
                            e.location = i
                            break
                        }
                        b.push(e)
                    })
                    _this.works = b
                })
            },
            bla(num){
                return num <= 9 ? "0" + num : num
            },
            formatedate(){
                let a = new Date().toLocaleDateString().split("/")
                a.forEach((e,idx)=>{
                    a[idx] = this.bla(e)
                })
                return a.join("-")
            },
            logout(){
                $.get('api.php?do=logout',function (){})
                alert("登出成功")
                location.href = "index.php"
            }
        },
        mounted(){
            for (i = 0; i < 24; i+=2) {
                this.time.push(i)
            }
            this.blaload()
            $('#editworkModal').modal("toggle")
            $('#editworkModal').modal("toggle")
            $('#filterworkModal').modal("toggle")
            $('#filterworkModal').modal("toggle")
        }
    }).mount("#app")
</script>
</body>
</html>