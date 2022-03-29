<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <?php include("head.php"); ?>
</head>
<body>
<div class="text-center" id="app">
    <div class="workblock" v-for="(item, index) in workdata" :style="{'height':(item.endTime-item.startTime)*50+'px','top': 259+item.startTime*50+'px','left':190+175*item.location+'px'}" @dragstart="dragstart(index)" draggable="true" @dblclick="showmodal('edit',index)">
        <div class="bruh">
            <div class="workblock-head">
                <p>{{bla(item.startTime)}}:00-{{bla(item.endTime)}}:00</p>
                <span :class="{badge:true,'badge-warning':item.speed == 'c', 'badge-success':item.speed == 'b', 'badge-info':item.speed == 'a'}">{{item.speed == "a" ? "普通件" : item.speed == "b" ? "速件" : "最速件"}}</span>
                <button class="close" @click="delwork(item.id)">&times;</button>
            </div>
            <div class="workblock-body">
                <span :class="{label:true,'label-warning':item.status == 'c', 'label-success':item.status == 'b', 'label-info':item.status == 'a'}">{{item.status == "a" ? "未處理" : item.status == "b" ? "處理中" : "已完成"}}</span>
                <p><b>{{item.name}}</b></p>
            </div>
        </div>
    </div>
    <img src="img/logo.png" class="logo">
    <h1>{{ date.split("-").join("/") }} TODO 工作表</h1>
    <input type="button" value="登出" class="btn logout" @click="logout()">
    <div class="btn-group">
        <input class="btn" type="button" value="新增工作" @click="showmodal('add',-1)">
        <input style="margin: 0;height: 100%" type="date" v-model="date" @input="blaload()">
        <input class="btn" type="button" value="設定篩選條件" @click="showfilter()">
    </div>
    <br><br>
    <div class="work">
        <table class="table table-striped">
            <thead>
            <tr>
                <td>時間</td>
                <td>工作計畫</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in time" @drop="drop()" @dragover="allowdrag($event)" @dragenter.preven>
                <td>{{item}}:00-{{item+2}}:00</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="workModal" class="modal fade hide" tabindex="-1">
        <div class="modal-header">
            <butoon class="close" data-dismiss="modal">&times;</butoon>
            <h3>工作編輯</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input class="width80" style="height: 30px" type="text" v-model="edit.name">
            <label>處理情形</label>
            <select class="width80" v-model="edit.status">
                <option value="a">未處理</option>
                <option value="b">處理中</option>
                <option value="c">已完成</option>
            </select>
            <label>優先情形</label>
            <select class="width80" v-model="edit.speed">
                <option value="a">普通件</option>
                <option value="b">速件</option>
                <option value="c">最速件</option>
            </select>
            <label>開始時間</label>
            <select class="width80" v-model="edit.startTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) >= edit.endTime">{{bla(i-1)}}:00</option>
            </select>
            <label>結束時間</label>
            <select class="width80" v-model="edit.endTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) <= edit.startTime">{{bla(i-1)}}:00</option>
            </select>
            <label>工作內容</label>
            <textarea class="width80" cols="30" rows="10" v-model="edit.description"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="save()">確認</button>
            <button class="btn" data-dismiss="modal">取消</button>
        </div>
    </div>
    <div id="filterModal" class="modal fade hide" tabindex="-1">
        <div class="modal-header">
            <butoon class="close" data-dismiss="modal">&times;</butoon>
            <h3>工作編輯</h3>
        </div>
        <div class="modal-body">
            <label>工作名稱</label>
            <input class="width80" style="height: 30px" type="text" v-model="filterdata.name">
            <label>處理情形</label>
            <select class="width80" v-model="filterdata.status">
                <option value="a">未處理</option>
                <option value="b">處理中</option>
                <option value="c">已完成</option>
            </select>
            <label>優先情形</label>
            <select class="width80" v-model="filterdata.speed">
                <option value="a">普通件</option>
                <option value="b">速件</option>
                <option value="c">最速件</option>
            </select>
            <label>開始時間</label>
            <select class="width80" v-model="filterdata.startTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) >= filterdata.endTime">{{bla(i-1)}}:00</option>
            </select>
            <label>結束時間</label>
            <select class="width80" v-model="filterdata.endTime">
                <option v-for="i in 25" :value="i-1" :disabled="(i-1) <= filterdata.startTime">{{bla(i-1)}}:00</option>
            </select>
            <label>工作內容</label>
            <textarea class="width80" cols="30" rows="10" v-model="filterdata.description"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" @click="blaload()">確認</button>
            <button class="btn" data-dismiss="modal">取消</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                userid: <?=$_SESSION['userid']?>,
                time: [],
                workdata:[],
                workrow:[],
                movedata: -1,
                date: this.formatdate(),
                filterdata:{
                    name:"",
                    startTime: 0,
                    endTime: 24,
                    status: "all",
                    speed: "all",
                    description: ""
                },
                edit:{
                    userid: -1,
                    id: -1,
                    date:"",
                    name:"",
                    startTime: 0,
                    endTime: 24,
                    status: "a",
                    speed: "a",
                    description: ""
                },
                formatedit:{
                    userid: -1,
                    id: -1,
                    date:"",
                    name:"",
                    startTime: 0,
                    endTime: 24,
                    status: "a",
                    speed: "a",
                    description: ""
                }
            }
        },
        methods:{
            logout(){
                $.get("api.php?do=logout",function (){})
                alert("登出成功")
                location.href="index.php"
            },
            bla(num){
                return num <= 9 ? "0" + num : num
            },
            showfilter(){
              $("#filterModal").modal('show')
            },
            dragstart(idx){
                this.movedata = idx
            },
            delwork(id) {
                $.post('api.php?do=delwork',{id:id},function (){})
                this.blaload()
            },
            save(){
                this.edit.userid = this.userid
                this.edit.date = this.date
              if (this.edit.id == -1){
                  $.post('api.php?do=addwork',this.$data.edit,function () {})
              }  else {
                  $.post('api.php?do=editwork',this.$data.edit,function (e) {})
              }
              this.blaload()
            },
            showmodal(what,idx) {
                if (what == "add"){
                    this.edit = this.formatedit
                }else {
                    this.edit = this.workdata[idx]
                }
                $('#workModal').modal("show")
            },
            async drop(){
                let idx = this.movedata
                await $.post('api.php?do=editwork',this.$data.workdata[idx],function (e) {})
                this.blaload()
            },
            allowdrag(event){
                let newTime = Math.floor((event.layerY-259)/50)
                let idx = this.movedata
                let timelong = this.workdata[idx].endTime - this.workdata[idx].startTime
                this.workdata[idx].startTime = newTime + timelong >= 24 ? 24 - timelong : newTime < 0 ? 0 : newTime
                this.workdata[idx].endTime = this.workdata[idx].startTime + timelong
                event.preventDefault()
            },
            formatdate(){
                let a = new Date().toLocaleDateString().split("/")
                a.forEach((e, idx)=>{
                    a[idx] = this.bla(e)
                })
                return a.join("-")
            },
            filterwork(){
                const _this = this
                $.post("api.php?do=worklist",{userid:this.userid},function (e) {
                    let a = []
                    e = JSON.parse(e)
                    e.forEach((b, idx)=>{
                        let bla = false;
                        if (b.date != _this.date) return
                        for (x in _this.filterdata) {
                            switch (x) {
                                case "startTime":
                                    if (b[x] < _this.filterdata[x]) bla = true
                                    break
                                case "endTime":
                                    if (b[x] > _this.filterdata[x]) bla = true
                                    break
                                case "status":
                                    if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") bla = true
                                    break
                                case "speed":
                                    if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") bla = true
                                    break
                                default:
                                    if (b[x].indexOf(_this.filterdata[x]) == -1) bla = true
                                    break
                            }
                        }
                        if (bla) return;
                        for (i = 0; i < _this.workrow.length; i++) {
                            let blabla = true
                            for (j = b.startTime; j < b.endTime; j++) {
                                if (_this.workrow[i][j-1]) blabla = false
                            }
                            if (i >= _this.workrow.length && !blabla){
                                _this.workrow.push([])
                                for (j = 0; j < 24; j++) {
                                    _this.workrow[_this.workrow.length].push(false)
                                }
                            }
                            if (!blabla) continue
                            Object.assign(b,{location:i})
                            for (j = b.startTime; j < b.endTime; j++) {
                                _this.workrow[i][j-1] = true
                            }
                            break
                        }
                        a.push(b)
                    })
                    _this.workdata = a
                })
            },
            resetlist(){
                this.workrow = []
                for (i = 0; i < 10; i++) {
                    this.workrow.push([])
                    for (j = 0; j < 24; j++) {
                        this.workrow[i].push(false)
                    }
                }
            },
            blaload(){
                this.resetlist()
                this.filterwork()
            }
        },
        mounted(){
            for (i = 0; i < 24; i += 2) {
                this.time.push(i)
            }
            this.blaload()
        }
    }).mount("#app")
</script>
</body>
</html>