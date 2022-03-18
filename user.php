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
    <div class="block" v-for="(item, index) in list()" :style="{'height': (item.endTime-item.startTime)*33.5 + 'px','z-index': 10,top:122 + (item.startTime) *33 + 'px','left': 100 + 175 * item.location + 'px'}" @dblclick="editwork(index)" draggable="true">
        <div class="blockhead">
            <p>{{bla(item.startTime)}}:00 - {{bla(item.endTime)}}:00</p>
            <span :class="{'badge':true,'badge-success':item.staus=='done','badge-warning':item.staus=='ing','badge-important':item.staus=='pending'}">{{item.staus == "done" ? "已完成" : item.staus == "ing" ? "處理中" : "未處理"}}</span>
            <button class="close" @click="delwork(item.id)">&times;</button>
        </div>
        <span :class="{'label':true,'label-success':item.speed=='normal','label-warning':item.speed=='fast','label-important':item.speed=='faster'}">{{item.speed == "normal" ? "普通件" : item.speed == "fast" ? "速件" : "最速件"}}</span>
        <b>{{item.name}}</b>
    </div>
    <div class="text-center">
    <h1>TODO 工作表</h1>
    <input class="btn" type="button" value="新增工作" @click="showmodal('新增工作','新增')">
        <table class="table work">
            <thead>
                <tr>
                    <td>時間</td>
                    <td style="width: 100px">工作計畫</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in tiemlist" @dragover.prevent @dragenter.prevent>
                    <td>{{bla(i)}}:00-{{bla(i+2)}}:00</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: block;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">{{work[0]}}</h3>
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
            <textarea class="addwork15" id="workdata" v-model="workdata" cols="30" rows="10"></textarea>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="button" :value="work[1]" @click="blabla">
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
                staus: "present",
                speed: "present",
                startTime: 0,
                endTime: 24,
                workdata: "",
                userid: <?=$_SESSION["userid"]?>
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
                this.staus = "present"
                this.speed = "present"
                this.startTime = 0
                this.endTime = 24
                this.workdata = ""
                $('#myModal').modal('show')
            },
            addwork(){
                if (this.name == "" || this.staus == "present" || this.speed == "present"){
                    alert("欄位不得為空")
                    return
                }
                const _this = this
                $("#myModal").modal("toggle")
                $.post("api.php?do=addwork",this.$data,function (a) {})
                this.resettime()
                this.loadworks()
            },
            editwork(idx){
                this.work = ["工作編輯","儲存"]
                this.name = this.datalist[idx].name
                this.staus = this.datalist[idx].staus
                this.speed = this.datalist[idx].speed
                this.startTime = this.datalist[idx].startTime
                this.endTime = this.datalist[idx].endTime
                this.edit = this.datalist[idx].id
                this.workdata = this.datalist[idx].workdata
                $('#myModal').modal('show')
            },
            delwork(id){
                $.post("api.php?do=delwork",{id:id},function (a){})
                this.resettime()
                this.loadworks()
            },
            blabla(){
                return this.work[0] == "新增工作" ? this.addwork() : this.savework()
            },
            savework(){
                $.post("api.php?do=savework",this.$data,function (a) {})
                $('#myModal').modal('toggle')
                this.resettime()
                this.loadworks()
            },
            list(){
                return this.datalist
            },
            loadworks(){
                const _this = this
                $.post("api.php?do=worklist",this.$data,function (a) {
                    a = JSON.parse(a)
                    a.forEach((e,idx)=>{
                        for (i = 0; i < _this.time.length ; i++) {
                            let bla = false
                            for (j = e.startTime; j < e.endTime; j++) {
                                if (_this.time[i][j-1]) bla = true
                            }
                            if (bla && i == _this.time.length -1){
                                this.time.push([])
                                for (j = 0; j < 24; j++) {
                                    this.time[i].push(false)
                                }
                            }
                            if (bla) continue
                            Object.assign(e,{location:i})
                            console.log(idx,i)
                            for (j = e.startTime+1; j < e.endTime; j++) {
                                _this.time[i][j-1] = true
                            }
                            break
                        }
                    })
                    _this.datalist = a
                })
            },
            resettime(){
                this.time = []
                for (i = 0; i < 10; i++) {
                    this.time.push([])
                    for (j = 0; j < 24; j++) {
                        this.time[i].push(false)
                    }
                }
            }
        },
        mounted(){
            for (i = 0; i < 24 ; i+=2) {
                this.tiemlist.push(i)
            }
            this.resettime()
            this.loadworks()
            $("#myModal").modal("toggle")
            $("#myModal").modal("toggle")
        }
    }).mount('#app')
</script>
</body>
</html>