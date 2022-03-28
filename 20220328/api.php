<?php
include ('pdo.php');
$P = $_POST;
switch ($_GET['do']){
    case "login":
        $result = query("SELECT * FROM user WHERE account = '{$P['account']}'");
        $row = fetch($result);
        if(rownum($result) >= 1){
            if($row['password'] == $P['password']){
                if($P['v'] == join($P['list2'] ?? [])){
                    $_SESSION['userid'] = $row['id'];
                    echo $row['groups'];
                }else{
                    echo "驗證碼錯誤";
                }
            }else{
                query("INSERT INTO userlog(userid, status) VALUES ()");
                echo "密碼錯誤";
            }
        }else{
            echo "帳號錯誤";
        }
        break;
    case "logout":
        echo "logout";
        break;
    case "userlog":
        echo "userlog";
        break;
    case "adduser":
        echo "adduser";
        break;
    case "edituser":
        echo "edituser";
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
        echo "userlist";
        break;
    case "worklist":
        echo "worklist";
        break;
    case "workdata":
        echo "workdata";
        break;
}