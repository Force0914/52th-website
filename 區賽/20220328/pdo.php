<?php
$conn = new PDO("mysql:host=localhost;dbname=52_1;charset=utf8;","admin","1234");

function query($query){
    global $conn;
    return $conn -> query($query);
}

function fetch($query){
    return $query -> fetch();
}

function fetchAll($query){
    return $query -> fetchAll();
}

function rownum($query){
    return $query -> rowCount();
}