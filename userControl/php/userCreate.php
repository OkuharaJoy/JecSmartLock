<?php

$name = $_POST['name'];
$mail = $_POST['mail'];
$password = $_POST['password'] ? hash('sha512', $_POST['password']) : "";
$tel = $_POST['tel'];
$permission = $_POST['role'];
$openRoomNumber = $_POST['open-room-number'];  // 学生の場合
$usePeriod = $_POST['use-period'];  // 学生の場合

// データベース接続
$dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
$dbuser = 'scott';
$dbpass = 'fh-p*KP2&C*QV#vdh4*2';

try {
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $tel = ($permission == 3) ? NULL : $_POST['tel'];  // 学生の場合、TELをNULLに設定

    // 学生の場合、利用期限を適切な形式に変換
    if ($permission == 3 && !empty($usePeriod)) {
        // 'T'を' 'に変換して、'YYYY-MM-DD HH24:MI:SS'形式に整形
        $usePeriod = str_replace('T', ' ', $usePeriod);
        // 時間部分が不足している場合にデフォルト値を追加する場合
        if (strpos($usePeriod, ' ') === false) {
            $usePeriod .= ' 00:00:00';  // 時間が指定されていない場合
        }
    }

    // SQL文
    if ($permission == 3 && !empty($openRoomNumber) && !empty($usePeriod)) {
        $sql = "INSERT INTO users (NAME, MAIL, PASS, TEL, PERMISSION, CLASSROOMID, USE_PERIOD) 
                VALUES (:NAME, :MAIL, :PASS, :TEL, :PERMISSION, :CLASSROOMID, TO_DATE(:USE_PERIOD, 'YYYY-MM-DD HH24:MI:SS'))";
    } else {
        $sql = "INSERT INTO users (NAME, MAIL, PASS, TEL, PERMISSION) 
                VALUES (:NAME, :MAIL, :PASS, :TEL, :PERMISSION)";
    }

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":NAME", $name, PDO::PARAM_STR);
    $stmt->bindValue(":MAIL", $mail, PDO::PARAM_STR);
    $stmt->bindValue(":PASS", $password, PDO::PARAM_STR);
    $stmt->bindValue(":TEL", $tel, PDO::PARAM_STR); // TELが空文字列または値に設定されます
    $stmt->bindValue(":PERMISSION", $permission, PDO::PARAM_INT);

    // 学生の場合のみ、施錠号室と利用期限をバインド
    if ($permission == 3 && !empty($openRoomNumber) && !empty($usePeriod)) {
        $stmt->bindValue(":CLASSROOMID", $openRoomNumber, PDO::PARAM_INT);
        $stmt->bindValue(":USE_PERIOD", $usePeriod, PDO::PARAM_STR);  // 変換後のUSE_PERIODをバインド
    }

    $rexec = $stmt->execute(); // SQL実行

    if ($rexec) {
        echo json_encode(['status' => 'success', 'name' => $name, 'mail' => $mail, 'success' => true]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ユーザーの登録に失敗しました。', 'success' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベース接続エラー: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => '予期しないエラーが発生しました: ' . $e->getMessage()]);
}

?>
