<?php
include ('pdo.php');
session_start();
$P = $_POST;
switch ($_GET['do']){
    case "login":
        $result = query("SELECT * FROM user WHERE account = '{$P['account']}'");
        $row = fetch($result);
        if(rownum($result) >= 1){
            if($row['password'] == $P['password']){
                if($P['v'] == join($P['list2'] ?? [])){
                    $_SESSION['userid'] = $row['id'];
                    query("INSERT INTO userlog(userid, status) VALUES ({$row['id']},'登入成功')");
                    echo $row['groups'];
                }else{
                    query("INSERT INTO userlog(userid, status) VALUES ({$row['id']},'驗證碼錯誤')");
                    echo "驗證碼錯誤";
                }
            }else{
                query("INSERT INTO userlog(userid, status) VALUES ({$row['id']},'密碼錯誤')");
                echo "密碼錯誤";
            }
        }else{
            echo "帳號錯誤";
        }
        break;
    case "logout":
        query("INSERT INTO userlog(userid, status) VALUES ({$_SESSION['userid']},'登出成功')");
        session_destroy();
        session_reset();
        echo "登出成功";
        break;
    case "userlog":
        $result = query("SELECT * FROM user WHERE userid = {$P['id']}");
        $row = fetchall($result);
        echo json_encode($row);
        break;
    case "adduser":
        query("INSERT INTO user(account, password) VALUES ('{$P['account']}','{$P['password']}')");
        break;
    case "edituser":
        query("UPDATE user SET account='{$P['account']}',password='{$P['password']}]',groups='{$P['groups']}' WHERE id = {$P['id']}");
        break;
    case "deluser":
        echo "deluser";
        break;
    case "addwork":
        echo "addwork";
        break;
    case "editwork":
        echo "editwork";
        break;
    case "delwork":
        echo "delwork";
        break;
    case "userlist":
        $result = query("SELECT * FROM user");
        $row = fetchall($result);
        echo json_encode($row);
        break;
    case "worklist":
        $result = query("SELECT * FROM works WHERE userid = {$P['userid']}");
        $row = fetchall($result);
        echo json_encode($row);
        break;
}