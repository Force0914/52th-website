<!doctype html>
<html lang="en">
<head>
    <script src="js/vue.js"></script>
    <script src="js/kjAjax.js"></script>
    <title>kjAjax</title>
</head>
<body>
<div id="app">
    <input type="text" maxlength="10" v-model="name" @input="api()"><br>
    <input type="number" min="0" v-model="age" @input="api()"><br>
    {{data}}
</div>
<script>
    let vue = Vue.createApp({
        data(){
            return{
                name: "abc",
                age: 15,
                data:""
            }
        },
        methods:{
            api(){
                const _this = this
                // $.post('testapi.php',this.$data,function (a){
                //     _this.data = a
                // })
            }
        },
        mounted() {
            this.api()
            $.get(`https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-9803D967-5A47-459F-8474-3E0960EC6550&sort=time`,function (a) {
                console.log(JSON.parse(a))
            })
        }
    }).mount("#app")
</script>
</body>
</html>