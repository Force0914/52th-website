<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <?php include("header.php");?>
    <title>TODO 工作管理系統</title>
</head>
<body>
<div id="app">
    <div class="block" v-for="(item, index) in list()" :style="{'height': (item.endTime-item.startTime)*32 + 2 + 'px','z-index': 1 + item.id,top:122 + (item.startTime) *33.5 + 'px','left': this.workcheck(index) ? '260px' : '100px'}">
        <div class="blockhead">
            <p>{{bla(item.startTime)}}:00 - {{bla(item.endTime)}}:00</p>
            <span :class="{'badge':true,'badge-success':item.staus=='done','badge-warning':item.staus=='ing','badge-important':item.staus=='pending'}">{{item.staus == "done" ? "已完成" : item.staus == "ing" ? "處理中" : "未處理"}}</span>
        </div>
        <span :class="{'label':true,'label-success':item.speed=='normal','label-warning':item.speed=='fast','label-important':item.speed=='faster'}">{{item.speed == "normal" ? "普通件" : item.speed == "fast" ? "速件" : "最速件"}}</span>
        <b>{{item.name}}</b>
    </div>
    <div class="text-center">
    <h1>{{this.date.getMonth()+1}} 月 {{this.date.getDate()}} 日工作計劃表</h1>
    <input class="btn" type="button" value="新增工作" @click="showmodal">
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
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">新增工作</h3>
        </div>
        <div class="modal-body">
            <input class="addwork15" type="text" v-model="name" placeholder="工作名稱">
            <select class="addwork" v-model="staus">
                <option value="present" disabled selected>處理狀態</option>
                <option value="pending">未處理</option>
                <option value="ing">處理中</option>
                <option value="done">已完成</option>
            </select>

            <select class="addwork" v-model="speed">
                <option value="present" disabled selected>優先順序</option>
                <option value="normal">普通件</option>
                <option value="fast">速件</option>
                <option value="faster">最速件</option>
            </select>

            <select class="addwork" v-model="startTime">
                <option disabled selected>開始時間</option>
                <option v-for="j in 25" :value="j-1" :disabled="j-1 >= endTime">{{ bla(j-1) }}:00</option>
            </select>

            <select class="addwork" v-model="endTime">
                <option disabled selected>結束時間</option>
                <option v-for="j in 25" :value="j-1" :disabled="j-1 <= startTime">{{ bla(j-1) }}:00</option>
            </select>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="button" value="新增" @click="addwork">
            <button class="btn" data-dismiss="modal">關閉</button>
        </div>
    </div>
</div>
<script>
    let vue = Vue.createApp({
        data() {
            return {
                tiemlist:[],
                date: new Date(),
                data: [[]],
                datalist:[],
                time: [[]],
                name:"",
                staus: "present",
                speed: "present",
                startTime: 0,
                endTime: 24,
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
            showmodal(){
                $('#myModal').modal('show')
            },
            addwork(){
                if (this.name == "" || this.staus == "present" || this.speed == "present"){
                    alert("欄位不得為空")
                    return
                }
                const _this = this
                $("#myModal").modal("toggle")
                this.datalist.push({id:this.datalist.length+2,name: _this.name,staus: _this.staus,speed: _this.speed,startTime: _this.startTime,endTime: _this.endTime})
            },
            list(){
                return this.datalist
            },
            loadworks(){
                const _this = this
                $.post("api.php?do=worklist",this.$data,function (a) {
                    _this.datalist = JSON.parse(a)
                    console.log(_this.datalist)
                })
            },
            workcheck(a){
                if (a == 0) return false
                if (this.datalist[a].startTime < this.datalist[a-1].endTime) return true
            }
        },
        mounted(){
            for (i = 0; i < 24 ; i+=2) {
                this.tiemlist.push(i)
                this.time[0].push(false,false)
            }
            this.loadworks()
            $("#myModal").modal("toggle")
            $("#myModal").modal("toggle")
        }
    }).mount('#app')
</script>
</body>
</html>