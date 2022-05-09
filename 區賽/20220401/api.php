<?php
include ('pdo.php');
session_start();
$P = $_POST;
switch ($_GET['do']){
    case "login":
        $result = query("SELECT * FROM user WHERE account = '{$P['account']}'");
        $row = fetch($result);
        if (rownum($result) >= 1){
            if ($row['password'] == $P['password']){
                if ($P['v'] == join($P['list2'] ?? [])){
                    $_SESSION['userid'] = $row['id'];
                    query("INSERT INTO userlog(userid, action) VALUES ('{$row['id']}','登入成功')");
                    echo $row['groups'];
                }else{
                    query("INSERT INTO userlog(userid, action) VALUES ('{$row['id']}','驗證碼錯誤')");
                    echo "圖形驗證碼有誤";
                }
            }else{
                query("INSERT INTO userlog(userid, action) VALUES ('{$row['id']}','密碼錯誤')");
                echo "密碼錯誤";
            }
        }else{
            echo "帳號錯誤";
        }
        break;
    case "logout":
        query("INSERT INTO userlog(userid, action) VALUES ('{$P['userid']}','登出成功')");
        session_destroy();
        session_reset();
        echo "登出成功";
        break;
    case "userlist":
        echo json_encode(fetchAll(query("SELECT * FROM user")));
        break;
    case "userlog":
        echo json_encode(fetchAll(query("SELECT * FROM userlog WHERE userid = {$P['userid']}")));
        break;
    case "adduser":
        query("INSERT INTO user(account, password, groups) VALUES ('{$P['account']}','{$P['password']}','{$P['groups']}')");
        break;
    case "edituser":
        query("UPDATE user SET account='{$P['account']}',password='{$P['password']}',groups='{$P['groups']}' WHERE id = {$P['id']}");
        break;
    case "deluser":
        query("DELETE FROM user WHERE id = {$P['userid']}");
        break;
    case "worklist":
        echo json_encode(fetchAll(query("SELECT * FROM works WHERE userid = {$P['userid']} ORDER BY startTime")));
        break;
    case "addwork":
        query("INSERT INTO works(userid, name, date, startTime, endTime, speed, status, description) VALUES ('{$P['userid']}','{$P['name']}','{$P['date']}','{$P['startTime']}','{$P['endTime']}','{$P['speed']}','{$P['status']}','{$P['description']}')");
        break;
    case "editwork":
        query("UPDATE works SET name='{$P['name']}', startTime='{$P['startTime']}',endTime='{$P['endTime']}',speed='{$P['speed']}',status='{$P['status']}',description='{$P['description']}' WHERE id = {$P['id']}");
        break;
    case "delwork":
        query("DELETE FROM works WHERE id = {$P['id']}");
        break;
}