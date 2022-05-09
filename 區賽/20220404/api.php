<?php
include ("pdo.php");
session_start();
$P = $_POST;
switch ($_GET['do']){
    case "login":
        $result = query("SELECT * FROM user WHERE account = '{$P['account']}'");
        $row = fetch($result);
        if (rownum($result) >= 1){
            if ($row['password'] == $P['password']){
                if (join($P['list2']) == $P['v']){
                    $_SESSION['userid'] = $row['id'];
                    query("INSERT INTO userrlog(userid, action) VALUES ({$row['id']},'登入成功')");
                    echo $row['groups'];
                }else{
                    query("INSERT INTO userrlog(userid, action) VALUES ({$row['id']},'驗證碼錯誤')");
                    echo "驗證碼錯誤";
                }
            }else{
                query("INSERT INTO userrlog(userid, action) VALUES ({$row['id']},'密碼錯誤')");
                echo "密碼錯誤";
            }
        }else{
            echo "帳號錯誤";
        }
        break;
    case "logout":
        query("INSERT INTO userlog(userid, action) VALUES ({$_SESSION['userid']},'登出成功')");
        session_destroy();
        session_reset();
        break;
    case "userlog":
        echo json_encode(fetchAll(query("SELECT * FROM userlog WHERE userid = {$P['userid']}")));
        break;
    case "userlist":
        echo json_encode(fetchAll(query("SELECT * FROM user")));
        break;
    case "adduser":
        query("INSERT INTO user(account, password, groups) VALUES ('{$P['account']}', '{$P['password']}', '{$P['groups']}')");
        break;
    case "edituser":
        query("UPDATE user SET account='{$P['account']}',password='{$P['password']}',groups='{$P['groups=']}' WHERE id = {$P['id']}");
        break;
    case "deluser":
        query("DELETE FROM user WHERE id = {$P['id']}");
        break;
    case "worklist":
        echo json_encode(fetchAll(query("SELECT * FROM work WHERE userid = {$P['userid']}")));
        break;
    case "addwork":
        query("INSERT INTO works(userid, date, name, startTime, endTime, speed, status, description) VALUES ('{$P['userid']}', '{$P['date']}', '{$P['name']}', '{$P['startTime']}', '{$P['endTime']}', '{$P['speed']}', '{$P['status']}', '{$P['description']}')");
        break;
    case "editwork":
        query("UPDATE works SET userid='{$P['userid']}',date='{$P['date']}',name='{$P['name']}',startTime='{$P['startTime']}',endTime='{$P['endTime']}',speed='{$P['speed']}',status='{$P['status']}',description='{$P['description']}' WHERE id = {$P['id']}");
        break;
    case "delwork":
        query("DELETE FROM works WHERE id = {$P['id']}");
        break;
}