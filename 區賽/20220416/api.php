<?php
include ("pdo.php");
$p = $_POST;
session_start();
switch ($_GET['do']){
    case "login":
        $result = query("SELECT * FROM user WHERE account = '{$p['account']}'");
        $row = fetch($result);
        if (rownum($result) >= 1){
            if ($p['password'] == $row['password']){
                if (join($p['list2'] ?? []) == $p['v']){
                    $_SESSION['userid'] = $row['id'];
                    query("INSERT INTO userlog(userid,action) VALUES ({$row['id']},'登入成功')");
                    echo $row['groups'];
                }else{
                    query("INSERT INTO userlog(userid,action) VALUES ({$row['id']},'圖形驗證碼有誤')");
                    echo "圖形驗證碼有誤";
                }
            }else{
                query("INSERT INTO userlog(userid,action) VALUES ({$row['id']},'密碼有誤')");
                echo "密碼有誤";
            }
        }else{
            echo "帳號有誤";
        }
        break;
    case "logout":
        query("INSERT INTO userlog(userid,action) VALUES ({$_SESSION['userid']},'登出成功')");
        session_destroy();
        session_reset();
        break;
    case "userlist":
        echo json_encode(fetchAll(query("SELECT * FROM user ORDER BY {$p['sortby']} {$p['bruh']}")));
        break;
    case "userlog":
        echo json_encode(fetchAll(query("SELECT * FROM userlog WHERE userid = {$p['id']}")));
        break;
    case "adduser":
        query("INSERT INTO user(name,account,password,groups) VALUES ('{$p['name']}','{$p['account']}','{$p['password']}','{$p['groups']}')");
        break;
    case "edituser":
        query("UPDATE user SET name = '{$p['name']}',account = '{$p['account']}',password = '{$p['password']}',groups = '{$p['groups']}' WHERE id= {$p['id']}");
        break;
    case "deluser":
        query("DELETE FROM user WHERE id = {$p['id']}");
        break;
    case "worklist":
        echo json_encode(fetchAll(query("SELECT * FROM works WHERE userid = {$_SESSION['userid']} ORDER BY startTime")));
        break;
    case "addwork":
        query("INSERT INTO works(userid,name, date, startTime, endTime, status, speed, description) VALUES ({$_SESSION['userid']},'{$p['name']}','{$p['date']}','{$p['startTime']}','{$p['endTime']}','{$p['status']}','{$p['speed']}','{$p['description']}')");
        break;
    case "editwork":
        query("UPDATE works SET name = '{$p['name']}',date = '{$p['date']}',startTime = '{$p['startTime']}',endTime = '{$p['endTime']}',status = '{$p['status']}',speed = '{$p['speed']}',description = '{$p['description']}' WHERE id = {$p['id']}");
        break;
    case "delwork":
        query("DELETE FROM works WHERE id = {$p['id']}");
        break;
}