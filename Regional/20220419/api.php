<?php
include("pdo.php");
session_start();
$p = $_POST;
switch ($_GET['do']){
    case "login":
        $result = query("SELECT * FROM user WHERE account = '{$p['account']}'");
        $row = fetch($result);
        if (rownum($result) >= 1){
            if ($p['password'] == $row['password']){
                if ($p['v'] == join($p['list2'] ?? [])){
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
        query("INSERT INTO userlog(userid,action) VALUES ({$_SESSION['userid']},'登入成功')");
        session_destroy();
        session_reset();
        break;
    case "userlist":
        echo json_encode(fetchAll(query("SELECT * FROM user")));
        break;
    case "userlog":
        echo json_encode(fetchAll(query("SELECT * FROM userlog WHERE userid = {$p['id']}")));
        break;
    case "adduser":
        query("INSERT INTO user(account, password, groups) VALUES ('{$p['account']}','{$p['password']}','{$p['groups']}')");
        break;
    case "edituser":
        query("UPDATE user SET account = '{$p['account']}',password = '{$p['password']}',groups = '{$p['groups']}' WHERE id = {$p['id']}");
        break;
    case "deluser":
        query("DELETE FROM user WHERE id = {$p['id']}");
        break;
    case "worklist":
        echo json_encode(fetchAll(query("SELECT * FROM works WHERE userid = {$p['userid']}")));
        break;
    case "addwork":
        query("INSERT INTO works(userid,name,startTime,endTime,status,speed,date,description) VALUES ('{$p['userid']}','{$p['name']}','{$p['startTime']}','{$p['endTime']}','{$p['status']}','{$p['speed']}','{$p['date']}','{$p['description']}')");
        break;
    case "editwork":
        query("UPDATE works SET userid = '{$p['userid']}',name = '{$p['name']}',startTime = '{$p['startTime']}',endTime = '{$p['endTime']}',status = '{$p['status']}',speed = '{$p['speed']}',date = '{$p['date']}',description = '{$p['description']}' WHERE id = {$p['id']}");
        break;
    case "delwork":
        query("DELETE FROM works WHERE id = {$p['id']}");
        break;
}