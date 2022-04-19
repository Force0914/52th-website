<!doctype html>
<html lang="en">

<head>
    <?php include ("head.php");?>
    <script src="js/vue.js"></script>
</head>

<body>
    <div id="app" class="text-center">
        <div @mousemove="mousemove" @mouseup="mouseup">
            <img src="img/logo.png" class="logo">
            <h1>一般會員專區</h1>
            <input type="button" value="登出" class="btn logout" @click="logout()">
            <div class="btn-group">
                <input type="button" value="新增工作" class="btn" @click="addwork()">
                <input type="date" style="margin: 0" v-model="date" @input="blaload()">
                <input type="button" value="設定篩選條件" class="btn" @click="filtermodal()">
            </div>
            <input type="button" value="預覽" class="btn" style="position: absolute;top: 100.5px;right: calc(5% + 60px)">
            <input type="button" value="編輯" class="btn" style="position: absolute;top: 100.5px;right: 5%" @click="editwork(this.select)">
            <div class="block" v-for="(item,index) in works" @click="this.select = index" @dblclick="editwork(index)" @mousedown="mousedown(index)"
                :style="{'top':196+item.startTime*50+'px','left':200+item.location*180+'px','height':(item.endTime-item.startTime)*50+'px'}" :class="{'onselect':this.select == index}">
                <div class="block-padding">
                    <div class="block-head">
                        <p>{{bla(item.startTime)}}:00-{{bla(item.endTime)}}:00</p>
                        <span :class="{'badge':true, 'badge-warning':item.status == 'a' ,'badge-info':item.status == 'b','badge-success':item.status == 'c'}">{{item.status == "a" ? "未處理" : item.status == "b" ? "處理中" : "已完成"}}</span>
                        <button class="close" @click="delwork(item.id)">&times;</button>
                    </div>
                    <div class="block-body">
                        <span :class="{'badge':true, 'badge-warning':item.speed == 'a' ,'badge-info':item.speed == 'b','badge-success':item.speed == 'c'}">{{item.speed == "a" ? "普通件" : item.speed == "b" ? "速件" : "最速件"}}</span>
                        <b>{{item.name}}</b>
                    </div>
                </div>
            </div>
            <div class="table-border">
                <table class="table" @click="this.select = -1">
                    <thead>
                        <tr>
                            <td>時間</td>
                            <td><p style="float: left;margin: 0 !important;">工作計畫</p><p style="float: right;margin: 0 !important;">(雙擊工作計畫用以編輯)</p></td>
                        </tr>
                    </thead>
                    <tbody class="work">
                        <tr v-for="i in time">
                            <td>{{bla(i)}}:00-{{bla(i+2)}}:00</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="editmodal" class="modal fade hide">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h3>工作編輯</h3>
                </div>
                <div class="modal-body">
                    <label>工作名稱</label>
                    <input type="text" class="width15" v-model="edit.name">
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
                    <label>開始時間</label>
                    <select class="width80" v-model="edit.startTime">
                        <option v-for="i in 25" :value="i-1" :disabled="i-1 >= edit.endTime">{{bla(i-1)}}:00</option>
                    </select>
                    <label>結束時間</label>
                    <select class="width80" v-model="edit.endTime">
                        <option v-for="i in 25" :value="i-1" :disabled="i-1 <= edit.startTime">{{bla(i-1)}}:00</option>
                    </select>
                    <label>工作內容</label>
                    <textarea cols="30" rows="10" class="width15" v-model="edit.description"></textarea>
                </div>
                <div class="modal-footer">
                    <input type="button" value="儲存" class="btn" data-dismiss="modal" @click="save()">
                    <input type="button" value="取消" class="btn" data-dismiss="modal" @click="blaload()">
                </div>
            </div>
            <div id="filtermodal" class="modal fade hide">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h3>工作編輯</h3>
                </div>
                <div class="modal-body">
                    <label>工作名稱</label>
                    <input type="text" class="width15" v-model="filterdata.name">
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
                    <label>開始時間</label>
                    <select class="width80" v-model="filterdata.startTime">
                        <option v-for="i in 25" :value="i-1" :disabled="i-1 >= filterdata.endTime">{{bla(i-1)}}:00
                        </option>
                    </select>
                    <label>結束時間</label>
                    <select class="width80" v-model="filterdata.endTime">
                        <option v-for="i in 25" :value="i-1" :disabled="i-1 <= filterdata.startTime">{{bla(i-1)}}:00
                        </option>
                    </select>
                    <label>工作內容</label>
                    <textarea cols="30" rows="10" class="width15" v-model="filterdata.description"></textarea>
                </div>
                <div class="modal-footer">
                    <input type="button" value="儲存" class="btn" data-dismiss="modal" @click="blaload()">
                    <input type="button" value="取消" class="btn" data-dismiss="modal">
                </div>
            </div>
        </div>
    </div>
    <script>
        let vue = Vue.createApp({
            data() {
                return {
                    userid:<?= $_SESSION['userid'] ?>,
                    date: this.formatedate(),
                    time: [],
                    times: [],
                    apiworks: [],
                    works: [],
                    movedata: false,
                    oldTime: -1,
                    edit: {},
                    filterdata: {
                        name: "",
                        startTime: 0,
                        endTime: 24,
                        speed: "all",
                        status: "all",
                        description: ""
                    },
                    select:-1
                }
            },
            methods: {
                blaload() {
                    this.select = -1
                    this.resettime()
                    this.workload()
                },
                filtermodal() {
                    $('#filtermodal').modal('show')
                },
                workload() {
                    const _this = this
                    let blabla = []
                    $.post('api.php?do=worklist', { userid: this.userid }, function (a) {
                        a = JSON.parse(a)
                        a.forEach((b, idx) => {
                            if (b.date != _this.date) return
                            for (x in _this.filterdata) {
                                switch (x) {
                                    case "startTime":
                                        if (b[x] < _this.filterdata[x]) return;
                                        break;
                                    case "endTime":
                                        if (b[x] > _this.filterdata[x]) return;
                                        break;
                                    case "status":
                                        if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") return;
                                        break;
                                    case "speed":
                                        if (b[x] != _this.filterdata[x] && _this.filterdata[x] != "all") return;
                                        break;
                                    default:
                                        if (b[x].indexOf(_this.filterdata[x]) == -1) return;
                                        break;
                                }
                            }
                            for (let i = 0; i < _this.times.length; i++) {
                                let c = false
                                for (j = b.startTime; j < b.endTime; j++) {
                                    if (_this.times[i][j]) c = true
                                }
                                if (_this.times.length-1 == i && c){
                                    _this.times.push([])
                                    for (j = 0; j < 24; j++) {
                                        _this.times[_this.times.length - 1].push(false)
                                    }
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
                sort() {
                    this.resettime();
                    let blabla = [];
                    this.works.forEach((b) => {
                        for (i = 0; i < this.times.length; i++) {
                            let c = false
                            for (j = b.startTime; j < b.endTime; j++) {
                                if (this.times[i][j]) c = true
                            }
                            if (this.times.length - 1 == i && c){
                                this.times.push([])
                                for (j = 0; j < 24; j++) {
                                    this.times[this.times.length - 1].push(false)
                                }
                            }
                            if (c) continue
                            for (j = b.startTime; j < b.endTime; j++) {
                                this.times[i][j] = true
                            }
                            b.location = i
                            break
                        }
                        blabla.push(b)
                    })
                    this.works = blabla;
                },
                resettime() {
                    this.times = []
                    for (i = 0; i < 10; i++) {
                        this.times.push([])
                        for (j = 0; j < 24; j++) {
                            this.times[i].push(false)
                        }
                    }
                },
                formatedate() {
                    let today = new Date().toLocaleDateString().split("/")
                    today.forEach((e, idx) => {
                        today[idx] = this.bla(e)
                    })
                    return today.join("-")
                },
                bla(num) {
                    return num <= 9 ? "0" + num : num
                },
                logout() {
                    $.get('api.php?do=logout', function () { })
                    alert("登出成功")
                    location.href = "index.php"
                },
                addwork() {
                    this.edit = {
                        id: -1,
                        userid: this.userid,
                        date: this.date,
                        name: "",
                        startTime: 0,
                        endTime: 24,
                        speed: "a",
                        status: "a",
                        description: ""
                    }
                    $("#editmodal").modal('show')
                },
                editwork(idx) {
                    if (idx == -1) return alert("請先選取一個工作計畫")
                    this.edit = this.works[idx]
                    this.edit.userid = this.userid
                    $("#editmodal").modal('show')
                },
                save() {
                    if (this.edit.id == -1) {
                        $.post('api.php?do=addwork', this.edit, function () {})
                    } else {
                        $.post('api.php?do=editwork', this.edit, function () {})
                    }
                    this.blaload()
                },
                delwork(id) {
                    if (!confirm("是否確認刪除工作計畫？")) return
                    $.post('api.php?do=delwork', { id: id }, function () {})
                    this.blaload()
                },
                mouseup() {
                    if (this.movedata === false) {
                        return
                    }
                    let idx = this.movedata
                    $.post('api.php?do=editwork', this.$data.works[idx], function () {})
                    this.movedata = false
                    this.blaload()
                },
                mousedown(idx) {
                    this.movedata = idx
                },
                mousemove(event) {
                    if (this.movedata === false) {
                        return
                    }
                    let idx = this.movedata
                    let timelong = this.works[idx].endTime - this.works[idx].startTime
                    let newTime = Math.floor((event.pageY - 196) / 50)
                    this.works[idx].startTime = newTime < 0 ? 0 : newTime > 24 - timelong ? 24 - timelong : newTime
                    this.works[idx].endTime = this.works[idx].startTime + timelong
                    this.sort();
                }
            },
            mounted() {
                for (i = 0; i < 24; i += 2) {
                    this.time.push(i)
                }
                this.blaload()
            }
        }).mount("#app")
    </script>
</body>

</html>