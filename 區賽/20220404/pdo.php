<?php
$conn = new PDO("mysql:host=localhost;dbname=52_4;charset=utf8;","admin","1234");

function query($query){
    global $conn;
    return $conn -> query($query);
}

function fetch($result){
    return $result -> fetch();
}

function fetchAll($result){
    return $result -> fetchAll();
}

function rownum($result){
    return $result -> rowCount();
}