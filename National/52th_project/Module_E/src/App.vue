<script setup>
import {ref,reactive,onMounted} from "vue";

let searchText = ref("")
let filter = reactive({
  class:null,
  text:""
})
let dialog = reactive({
  addStudent:false,
  addClass:false,
  edit:false
})
let dialogData = reactive({
  student:{},
  class:{
    name:""
  }
})
let studentList = reactive([])
let classList = reactive([])

const getAllStudent = (del) => {
    return del ? studentList : studentList.filter(data=>!data.del)
}

const getClassStudent = (key) =>{
  return studentList.filter(data => data.class.indexOf(key) != -1 && !data.del)
}

const getDelStudent = () =>{
  return studentList.filter(data => data.del)
}

const load = async () => {
  let db = await getDB();
  let classListTransaction = db.transaction("classList", "readwrite");
  let studentListTransaction = db.transaction("studentList","readwrite")
  let classListObjectStore = classListTransaction.objectStore("classList");
  let studentListObjectStore = studentListTransaction.objectStore("studentList");
  let classListRequest = classListObjectStore.get("data");
  classListRequest.onsuccess = (event) => {
    if (classListRequest.result != undefined){
      classList.splice(0,classList.length,...JSON.parse(classListRequest.result))
    }
  };
  let studentListRequest = studentListObjectStore.get("data");
  studentListRequest.onsuccess = (event) => {
    if (studentListRequest.result != undefined){
      studentList.splice(0,studentList.length,...JSON.parse(studentListRequest.result))
    }
  };
}

const getDB = async () => {
  return new Promise((resolve) => {
    let request = window.indexedDB.open("52", 1);
    request.onupgradeneeded = function (event) {
      let db = request.result;
      let classListObjectStore = db.createObjectStore("classList");
      let studentListObjectStore = db.createObjectStore("studentList")
    };
    request.onsuccess = function (event) {
      resolve(request.result);
    };
  });
}

const save = async () => {
  let db = await getDB();
  let classListTransaction = db.transaction("classList", "readwrite");
  let studentListTransaction = db.transaction("studentList","readwrite")
  let classListObjectStore = classListTransaction.objectStore("classList");
  let studentListObjectStore = studentListTransaction.objectStore("studentList");
  classListObjectStore.put(JSON.stringify(classList), "data");
  studentListObjectStore.put(JSON.stringify(studentList),"data")
}

const dialogClass = () =>{
  dialogData.class.name = ""
  dialog.addClass = true
  dialog.edit = false
}

const dialogStudent = () =>{
  dialogData.student = {
    avatar:"data:image/png;base64,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BFWjnLTu3fCW3IpLb1jRteryCzr61l+ZyvVda1nucqWtnNI6Bco+tapzuDhw6nMikYipiYmLcRwM14qjz5dGDu4onNh9ZOiVJ4YKB54pmbBqcNON0d54SQxUUGoYkSKTkxEdHVBsn7mfqbZLD11diUCViI1IlsQ/yJHI2746zE0khL4QRWJ19Fjd697Q0bVyYy7Xf3Fvpmd1v5ft6fOyy9Y7qY512nJySukUSrsoLFWX0RbEIMQiJkBMzcThZFgrHvZL+cN+eXy0MjGYLw4fyBdO7J6cOLa9HJfGY2xb4XgKbU/t9HqqPEUUowhlKpWYVGrJdXwDS5cADXR1gSMQopMcQ9WFSDeQIrEKU2y+gImlsZIgFtAaq3OZnepc7njZXsfL9ji2m7WV5Whd98hNHIrEkQn9UhRUxkO/NBbWJofDeHIsEVqyFNiOwnIU2pp6wK/x7RioodQ4IpMkQZ1EoWkJzPMzYekTYCqWLWsEkmxEPERyQBenkuG1TkAjA0hMki0qjav0plwfnqT51n+SFNAzpHk13t0Y6T5KTSBSRCkfy0pu6JrH6dx249wiwFRkMolohedZSdRQPIQsSXTRJSFE4zrw0+s5nSNwpueEZDTHQECyp1FGqRoiIY4TIwL5fLtb5Kxw7hLgdPT0QBSB1hZJ5zsoHASHxEJ4JP7DVFKcmjf2amdHQI3ElIcoQoQQkRhjYrTmbM7hLUW8fggwHbq6khPNUVTveFGIJJKdUrfxygiGJCVYKUEwKCW4LgwNLejz53Ee53Ee53EeSxT/HxDhOP3WpP8TAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTAzLTIzVDE4OjI5OjQzKzAxOjAw8/MIQQAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wMy0yM1QxODoyOTo0MyswMTowMIKusP0AAABGdEVYdHNvZnR3YXJlAEltYWdlTWFnaWNrIDYuNy44LTkgMjAxNi0wNi0xNiBRMTYgaHR0cDovL3d3dy5pbWFnZW1hZ2ljay5vcmfmvzS2AAAAGHRFWHRUaHVtYjo6RG9jdW1lbnQ6OlBhZ2VzADGn/7svAAAAGHRFWHRUaHVtYjo6SW1hZ2U6OmhlaWdodAA1MTLA0FBRAAAAF3RFWHRUaHVtYjo6SW1hZ2U6OldpZHRoADUxMhx8A9wAAAAZdEVYdFRodW1iOjpNaW1ldHlwZQBpbWFnZS9wbmc/slZOAAAAF3RFWHRUaHVtYjo6TVRpbWUAMTUyMTgyNjE4Mzw6cDsAAAATdEVYdFRodW1iOjpTaXplADI4LjlLQkIy3h3DAAAAQ3RFWHRUaHVtYjo6VVJJAGZpbGU6Ly8uL3VwbG9hZHMvNTYvVGp0YUdIMC8xMzc4L2F2YXRhcmRlZmF1bHRfOTI4MjQucG5nMxOZQgAAAABJRU5ErkJggg==",
    name:{
      last:"",
      first:"",
      full: ""
    },
    student_id:"",
    email:"",
    phone:"",
    class:[],
    del: false
  }
  dialog.addStudent = true
}

const editStudent = (idx) => {
  dialogData.student = studentList.filter(data => true)[idx]
  dialog.addStudent = true
  dialog.edit = true
}

const saveStudent = (edit) => {
  dialog.addStudent = false
  dialog.edit = false
  if (!edit) {
    dialogData.student.name.full = dialogData.student.name.last + dialogData.student.name.first
    studentList.push(dialogData.student)
  }else{
    dialogData.student.name.full = dialogData.student.name.last + dialogData.student.name.first
    studentList[dialogData.student.id] = dialogData.student
  }
  save()
}

const saveClass = () => {
  dialog.addClass = false
  dialog.edit = false
  classList.push({
    "name":dialogData.class.name
  })
  save()
}

const filterStudent = () =>{
  let filterData = []
  studentList.forEach((data,idx)=>{
    data.id = idx
    let issearch = false
    let isclass = false
      if (filter.class == null){
        isclass = true
      }else if(filter.class == "garbage"){
        if (data.del) isclass = true
      }else{
        if (data.class.includes(filter.class)) isclass = true
      }
      if (data.name.first.includes(filter.text)) issearch = true
      if (data.name.last.includes(filter.text)) issearch = true
      if (data.name.full.includes(filter.text)) issearch = true
      if (data.phone.includes(filter.text)) issearch = true
      if (data.student_id.toString().includes(filter.text)) issearch = true
      if (data.email.includes(filter.text)) issearch = true
    if (issearch && isclass){
      if (filter.class == 'garbage' || !data.del){
        filterData.push(data)
      }
    }
  })
  return filterData
}

const delStudent = (idx) => {
  studentList[idx].del = true
  save()
}

const unDoDelStudent = (idx) => {
  studentList[idx].del = false
  save()
}

const removeStudent = (idx) => {
  studentList.splice(idx,1)
  save()
}

const openAvatarUpload = () => {
  document.getElementById("avatar_upload").click()
}

const avatarUpload = (e) =>{
  const reader = new FileReader()
  reader.onload = () => {
    let img = document.createElement("img")
    img.onload = () => {
      let canvas = document.createElement("canvas")
      canvas.width = 120
      canvas.height = 120
      let ctx = canvas.getContext("2d")
      ctx.drawImage(img,0,0,120,120)
      dialogData.student.avatar = canvas.toDataURL(e.target.files[0].type)
    }
    img.src = reader.result
  }
  reader.readAsDataURL(e.target.files[0])
}

onMounted(()=>{
  load()
})
</script>

<template>
  <header id="header">
    <h1 id="logo" class="logo">學生資料</h1>
    <form id="searchForm" @submit.prevent>
      <input type="text" placeholder="搜尋" name="search" v-model="searchText" autocomplete="off" @keydown.enter="filter.text = searchText" @keyup.enter="test">
    </form>
  </header>
  <div class="row">
    <aside id="aside">
      <button id="addStudent" @click="dialogStudent()">新增學生</button>
      <ul id="studentList" class="list">
        <li :class="{item:true,current:filter.class == null}" @click="filter.class = null" >所有學生<span class="num">{{ getAllStudent(false).length }}</span></li>
      </ul>
      <hr>
      <ul id="classList" class="list">
        <li v-for="(data, key) in classList" :class="{item:true,current:filter.class == key}" @click="filter.class = key">
          {{data.name}}
          <span class="num">{{ getClassStudent(key).length }}</span>
        </li>
        <li id="addClass" class="item" @click="dialogClass">建立班級</li>
      </ul>
      <hr>
      <ul class="list">
        <li :class="{item:true,current:filter.class == 'garbage'}" @click="filter.class = 'garbage'">垃圾桶<span class="num">{{ getDelStudent().length }}</span></li>
      </ul>
    </aside>
    <main id="main">
      <table class="contacts">
        <thead>
          <tr class="contact">
            <th>大頭貼</th>
            <th>姓名</th>
            <th>學號</th>
            <th>電子郵件</th>
            <th>電話號碼</th>
            <th>班級</th>
            <th>功能按鈕</th>
          </tr>
        </thead>
        <tbody>
          <tr class="contact" v-for="(data) in filterStudent()">
            <td>
              <div>
                <img :src="data.avatar" class="avatar">
              </div>
            </td>
            <td>
              <p class="fullname">{{ data.name.full }}</p>
            </td>
            <td>
              <p class="student_id">{{ data.student_id }}</p>
            </td>
            <td>
              <p class="email">{{ data.email }}</p>
            </td>
            <td>
              <p class="phone">{{ data.phone }}</p>
            </td>
            <td>
              <p class="class">{{ data.class[0] == undefined ? "" : classList[data.class[0]].name}}</p>
            </td>
            <td>
              <p class="actions">
                <button class="hide edit" v-if="!data.del" @click="editStudent(data.id)">編輯</button>
                <button class="hide delete" v-else @click="unDoDelStudent(data.id)">還原</button>
                <button class="hide delete" v-if="!data.del" @click="delStudent(data.id)">刪除</button>
                <button class="hide delete" v-else @click="removeStudent(data.id)">清除</button>
              </p>
            </td>
          </tr>
        </tbody>
      </table>
      <p class="message" v-if="filterStudent().length === 0">{{filter.text ===  '' ? "目前還沒有任何學生" : "在你的學生中找不到相符的搜尋結果"}}</p>
    </main>
  </div>
  <Transition name="fade">
    <div class="dialog" id="dialog" v-if="dialog.addClass || dialog.addStudent">
      <div v-if="dialog.addClass">
        <h2 class="title">建立班級</h2>
        <form class="newClass" @submit.prevent>
          <input type="text" name="name" class="newinput" v-model="dialogData.class.name" placeholder="班級名稱" autocomplete="off">
          <div class="flex">
            <button type="button" class="close" style="margin: 12px;"
                    @click="dialog.addClass = false">取消</button>
            <button type="button" class="submit" style="margin: 12px;"
                    :disabled="dialogData.class.name.trim() == ''" @click="saveClass()">儲存</button>
          </div>
        </form>
      </div>
      <div v-else-if="dialog.addStudent">
        <form class="newContact" @submit.prevent>
          <h2 class="title">建立學生</h2>
          <hr>
          <div class="form">
            <div class="name">
              <input type="text" class="newinput" placeholder="姓氏" name="last_name" autocomplete="off" v-model="dialogData.student.name.last">
              <input type="text" class="newinput" placeholder="名字" name="first_name" autocomplete="off" v-model="dialogData.student.name.first" required>
              <img :src="dialogData.student.avatar" class="avatar_preview" @click="openAvatarUpload()">
              <input type="file" id="avatar_upload" class="avatar" accept=".bmp,.png,.jpg,.jpeg,.gif" style="display: none;" @input="avatarUpload">
            </div>
            <div class="halfhalf">
              <input type="text" name="student_id" class="newinput" placeholder="學號" autocomplete="off" v-model="dialogData.student.student_id">
              <input type="email" name="email[]" class="newinput" placeholder="電子郵件" autocomplete="off" v-model="dialogData.student.email">
            </div>
            <input type="tel" name="phone[]" class="newinput" placeholder="電話" autocomplete="off" v-model="dialogData.student.phone">
            <select class="class newselect" name="class" multiple v-model="dialogData.student.class">
              <option value="" selected disabled>請選擇班級</option>
              <option v-for="(data, key) in classList" :value="key">{{data.name}}</option>
            </select>
            <textarea name="note" class="newtextarea" placeholder="備註" autocomplete="off" v-model="dialogData.student.note"></textarea>
          </div>
          <hr>
          <div class="flex">
            <button type="button" class="close" style="margin: 12px;" @click="dialog.addStudent = false;load()">取消</button>
            <button type="submit" class="submit" style="margin: 12px;" @click="saveStudent(dialog.edit)">儲存</button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<style>
@import './assets/style.css';
</style>