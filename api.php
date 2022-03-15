<?php
session_start();
include("pdo.php");
$P = $_POST;
switch ($_GET['do']){
    case "login":
        $result = query("SELECT * FROM user WHERE account = '{$P['account']}'");
        $row = fetch($result);
        if (rownum($result) >=1 ){
            $_SESSION['userid'] = $row["id"];
            if($row["password"] == $P['password']){
                if ($P["v"] == implode("",($P["list2"] ?? [""]))){
                    $_SESSION['groups'] = $row["groups"];
                    query("INSERT INTO userlog (userid,message,action) VALUES ({$_SESSION["userid"]},'登入成功','login')");
                    echo $row["groups"];
                }else{
                    query("INSERT INTO userlog (userid,message,action) VALUES ({$_SESSION["userid"]},'圖形驗證碼錯誤','logfuk')");
                    echo "圖形驗證碼錯誤";
                }
            }else{
                query("INSERT INTO userlog (userid,message,action) VALUES ({$_SESSION["userid"]},'密碼有誤','logfuk')");
                echo "密碼有誤";
            }
        }else{
            echo "帳號有誤";
        }
        break;
    case "logout":
        query("INSERT INTO userlog (userid,message,action) VALUES ({$_SESSION["userid"]},'登出成功','logout')");
        session_unset();
        session_destroy();
        break;
    case "userlist":
        $result = query("SELECT * FROM user");
        $row = fetchall($result);
        echo json_encode($row);
        break;
    case "worklist":
        $result = query("SELECT * FROM works WHERE userid = {$P["userid"]} ORDER BY startTime");
        $row = fetchall($result);
        echo json_encode($row);
        break;
    case "userlog":
        $result = query("SELECT * FROM userlog");
        $row = fetchall($result);
        echo json_encode($row);
        break;
    case "changename":
        query("UPDATE user SET account='{$P["set"]}' WHERE id = {$P["userid"]}");
        break;
    case  "changepassword":
        query("UPDATE user SET password='{$P["set"]}' WHERE id = {$P["userid"]}");
        break;
    case "changegroups":
        query("UPDATE user SET groups='{$P["set"]}' WHERE id = {$P["userid"]}");
        break;
    case "deluser":
        query("DELETE FROM user WHERE id = {$P["userid"]}");
        break;
    case "createuser":
        query("INSERT INTO user(account, password) VALUES ('{$P["account"]}','{$P["password"]}')");
        break;
}