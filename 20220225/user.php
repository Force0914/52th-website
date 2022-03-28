<?php
session_start();
if (!isset($_SESSION["userid"])){
    header("Location:index.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php include("header.php");?>
    <title>TODO 工作管理系統</title>
</head>
<body>
<div id="app">
    <input type="button" value="登出" class="logout btn" @click="logout">
    <div class="block" v-for="(item, index) in datalist" :style="{'height': (item.endTime-item.startTime)*50 + 'px',top:241 + (item.startTime) *50 + 'px','left': 170 + 190 * item.location + 'px'}" :id="'block' + index" @dblclick="editwork(index)" @dragstart="onStartDrag(index)" draggable="true">
        <div class="blockhead" style="padding-top: 5px;padding-left: 5px">
            <p>{{bla(item.startTime)}}:00 - {{bla(item.endTime)}}:00</p>
            <span :class="{'badge':true,'badge-success':item.staus=='done','badge-warning':item.staus=='ing','badge-important':item.staus=='pending'}">{{item.staus == "done" ? "已完成" : item.staus == "ing" ? "處理中" : "未處理"}}</span>
            <button class="close" @click="delwork(item.id)">&times;</button>
        </div>
        <div style="padding-left: 5px">
            <span :class="{'label':true,'label-success':item.speed=='normal','label-warning':item.speed=='fast','label-important':item.speed=='faster'}">{{item.speed == "normal" ? "普通件" : item.speed == "fast" ? "速件" : "最速件"}}</span>
            <b style="padding-left: 5px">{{item.name}}</b>
        </div>
    </div>
    <div class="text-center">
        <img src="img/logo.png" class="logo">
    <h1>TODO 工作表</h1>
        <div class="btn-group">
            <input class="btn" type="button" value="新增工作" @click="showmodal('新增工作','新增')">
            <input class="btn" type="button" value="設定篩選條件" @click="filterModal()">
        </div>
        <div class="line">
            <table class="table table-striped work">
                <thead>
                <tr>
                    <td>時間</td>
                    <td>工作計畫</td>
                </tr>
                </thead>
                <tbody @drop="blaload()" @dragover="allowDrop($event)" @dragenter.preven>
                <tr v-for="i in tiemlist">
                    <td>{{bla(i)}}:00-{{bla(i+2)}}:00</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="blaModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="blaModalLabel" aria-hidden="false" style="display: block;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="blaModalLabel">{{work[0]}}</h3>
        </div>
        <div class="modal-body addworkmodal">
            <label for="name">工作名稱： </label>
            <input class="addwork15" id="name" type="text" v-model="name"><br>
            <label for="staus">處理狀態： </label>
            <select class="addwork" id="staus" v-model="staus">
                <option value="pending">未處理</option>
                <option value="ing">處理中</option>
                <option value="done">已完成</option>
            </select><br>
            <label for="speed">優先順序： </label>
            <select class="addwork" id="speed" v-model="speed">
                <option value="normal">普通件</option>
                <option value="fast">速件</option>
                <option value="faster">最速件</option>
            </select><br>
            <label for="startTime">開始時間： </label>
            <select class="addwork" id="startTime" v-model="startTime">
                <option v-for="j in 25" :value="j-1" :disabled="j-1 >= endTime">{{ bla(j-1) }}:00</option>
            </select><br>
            <label for="endTime">結束時間： </label>
            <select class="addwork" id="endTime" v-model="endTime">
                <option v-for="j in 25" :value="j-1" :disabled="j-1 <= startTime">{{ bla(j-1) }}:00</option>
            </select><br>
            <label for="workdata">工作內容： </label>
            <textarea class="addwork15" id="workdata" v-model="workdata" cols="30" rows="9"></textarea>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="button" :value="work[1]" @click="blabla">
            <button class="btn" data-dismiss="modal">關閉</button>
        </div>
    </div>
    <div id="filterModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="blaModalLabel" aria-hidden="false" style="display: block;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="filterModalLabel">篩選條件</h3>
        </div>
        <div class="modal-body addworkmodal">
            <label for="name">工作名稱： </label>
            <input class="addwork15" id="name" type="text" v-model="filterdata.name"><br>
            <label for="staus">處理狀態： </label>
            <select class="addwork" id="staus" v-model="filterdata.staus">
                <option value="all">全部狀態</option>
                <option value="pending">未處理</option>
                <option value="ing">處理中</option>
                <option value="done">已完成</option>
            </select><br>
            <label for="speed">優先順序： </label>
            <select class="addwork" id="speed" v-model="filterdata.speed">
                <option value="all">全部狀態</option>
                <option value="normal">普通件</option>
                <option value="fast">速件</option>
                <option value="faster">最速件</option>
            </select><br>
            <label for="startTime">開始時間： </label>
            <select class="addwork" id="startTime" v-model="filterdata.startTime">
                <option v-for="j in 25" :value="j-1" :disabled="j-1 >= filterdata.endTime">{{ bla(j-1) }}:00</option>
            </select><br>
            <label for="endTime">結束時間： </label>
            <select class="addwork" id="endTime" v-model="filterdata.endTime">
                <option v-for="j in 25" :value="j-1" :disabled="j-1 <= filterdata.startTime">{{ bla(j-1) }}:00</option>
            </select><br>
            <label for="workdata">工作內容： </label>
            <textarea class="addwork15" id="workdata" v-model="filterdata.workdata" cols="30" rows="9"></textarea>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="button" value="篩選" data-dismiss="modal" @click="blaload()">
            <button class="btn" data-dismiss="modal">關閉</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data() {
            return {
                tiemlist:[],
                data: [[]],
                datalist:[],
                edit:0,
                time: [],
                work:[],
                name:"",
                staus: "",
                speed: "",
                startTime: 0,
                endTime: 24,
                workdata: "",
                filterdata:{
                    name: "",
                    staus: "all",
                    speed: "all",
                    startTime: 0,
                    endTime: 24,
                    workdata: ""
                },
                userid: <?=$_SESSION["userid"]?>,
                movedata: -1
            }
        },
        methods:{
            logout(){
                $.get("api.php?do=logout",function (){})
                alert("登出成功");
                location.href="index.php"
            },
            bla(num){
                if (num <=9){
                    num = "0" + num.toString()
                }
                return num.toString()
            },
            showmodal(work,btn){
                this.work = [work,btn]
                this.name = ""
                this.staus = ""
                this.speed = ""
                this.startTime = 0
                this.endTime = 24
                this.workdata = ""
                $('#blaModal').modal('show')
            },
            filterModal(){
                $('#filterModal').modal('show')
            },
            filterwork(){
                const _this = this
                let filterdata = this.filterdata
                let filterrow = []
                $.post("api.php?do=worklist",this.$data,async function (b) {
                    b = JSON.parse(b)
                    await b.forEach((e)=>{
                        let blabla = true
                        for (let bruh in filterdata) {
                            let papaya = filterdata[bruh]
                            if (!blabla) break
                            switch (bruh) {
                                case "startTime":
                                    if (e.startTime < papaya) blabla = false
                                    break
                                case "endTime":
                                    if (e.endTime > papaya) blabla = false
                                    break
                                case "staus":
                                    if (papaya != "all" && e.staus != papaya) blabla = false
                                    break
                                case "speed":
                                    if (papaya != "all" && e.speed != papaya) blabla = false
                                    break
                                default:
                                    if (papaya != ""){
                                        if(e[bruh].indexOf(papaya) == -1) blabla = false
                                    }
                                    break
                            }
                        }
                        if (!blabla) return
                        for (i = 0; i < _this.time.length ; i++) {
                            let bla = false
                            for (j = e.startTime; j < e.endTime; j++) {
                                if (_this.time[i][j]) bla = true
                            }
                            if (bla && i == _this.time.length -1){
                                this.time.push([])
                                for (j = 0; j < 24; j++) {
                                    this.time[i].push(false)
                                }
                            }
                            if (bla) continue
                            Object.assign(e,{location:i})
                            filterrow.push(e)
                            for (j = e.startTime; j < e.endTime; j++) {
                                _this.time[i][j] = true
                            }
                            break
                        }
                    })
                    _this.datalist = filterrow
                })
            },
            addwork(){
                if (this.name == "" || this.staus == "" || this.speed == ""){
                    alert("欄位不得為空")
                    return
                }
                $("#blaModal").modal("toggle")
                $.post("api.php?do=addwork",this.$data,function (a) {})
                this.blaload()
            },
            editwork(idx){
                this.work = ["工作編輯","儲存"]
                this.edit = this.datalist[idx].id
                for (let bruh in this.datalist[idx]) {
                    this[bruh] = this.datalist[idx][bruh]
                }
                $('#blaModal').modal('show')
            },
            delwork(id){
                $.post("api.php?do=delwork",{id:id},function (a){})
                this.blaload()
            },
            blabla(){
                return this.work[0] == "新增工作" ? this.addwork() : this.savework()
            },
            savework(){
                $.post("api.php?do=savework",this.$data,function (a) {})
                $("#blaModal").modal("toggle")
                this.blaload()
            },
            resettime(){
                this.time = []
                for (i = 0; i < 10; i++) {
                    this.time.push([])
                    for (j = 0; j < 24; j++) {
                        this.time[i].push(false)
                    }
                }
            },
            onStartDrag(idx){
                this.movedata = idx
            },
            allowDrop(event){
                let newTime = Math.floor((event.layerY - 241)/50)
                if (this.datalist[this.movedata].startTime != newTime) {
                    let timelong = this.datalist[this.movedata].endTime - this.datalist[this.movedata].startTime
                    this.datalist[this.movedata].startTime = newTime >= 23 ? 23 : (newTime + timelong) >= 24 ? (24 - timelong) : newTime
                    this.datalist[this.movedata].endTime = (newTime + timelong) >= 24 ? 24 : (newTime + timelong)
                    $.post('api.php?do=updatetime',this.$data.datalist[this.movedata],function (e) {})
                }
                event.preventDefault()
            },
            blaload(){
                this.resettime()
                this.filterwork()
            },
        },
        mounted(){
            for (i = 0; i < 24 ; i+=2) {
                this.tiemlist.push(i)
            }
            this.blaload()
            $("#blaModal").modal("toggle")
            $("#blaModal").modal("toggle")
            $("#filterModal").modal("toggle")
            $("#filterModal").modal("toggle")
        }
    }).mount('#app')
</script>
</body>
</html>