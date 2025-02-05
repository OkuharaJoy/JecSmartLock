<?php
$dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
    $dbuser = 'scott';
    $dbpass = 'fh-p*KP2&C*QV#vdh4*2';
    $select_sql = 'select NAME,MAIL,TEL,PERMISSION,USE_PERIOD from users';
    $delete_spl = "DELETE FROM users WHERE NAME= :";
    $users = array();
    $err="";
    $i=0;
    try{
        $dbh = new PDO($dsn, $dbuser, $dbpass);
        $err .= 'データベースに接続しました。'; 
        $stmt = $dbh->query($select_sql);

        while($row = $stmt->fetch()){
            $user = array();
            $user['name'] = $row['NAME'];
            $user['mail'] = $row['MAIL'];
            $user['tel'] = $row['TEL'];
            $user['permission'] = $row['PERMISSION'];
            $user['use_period'] = $row['USE_PERIOD'];
            $users[$i++] = $user;
        }
        $stmt=null; $dbh=null;
    }catch (PDOException $e){
        $err .= '接続に失敗しました: ' . $e->getMessage();
    }
    ?>