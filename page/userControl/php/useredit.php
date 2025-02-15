<?php
header('Content-Type: application/json');

// データベース接続情報
$dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
$dbuser = 'scott';
$dbpass = 'fh-p*KP2&C*QV#vdh4*2';

try {
    // 必要なPOSTデータを受け取る
    $userId = $_POST['userId'] ?? null;
    $name = $_POST['name'] ?? '';
    $mail = $_POST['mail'] ?? '';
    $pass = $_POST['password'] ?? '';  
    $tel = $_POST['tel'] ?? '';
    $permission = $_POST['role'] ?? '';
    $openRoomNumber = $_POST['userclassroom'] ?? null;
    $usePeriod = $_POST['use_period'] ?? null;

    // デバッグ用ログ
    error_log("Received Data - userId: $userId, name: $name, mail: $mail, tel: $tel, permission: $permission, openRoomNumber: $openRoomNumber, usePeriod: $usePeriod");

    // ユーザーIDがない場合エラーを返す
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'ユーザーIDが送信されていません']);
        exit;
    }

    // データベース接続
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // use_period の NULL 対応と形式変換（Tをスペースに変換）
    if (!empty($usePeriod)) {
        $usePeriod = str_replace('T', ' ', $usePeriod);
    } else {
        $usePeriod = null;
    }

    // open_room_number の NULL 対応
    if (!empty($openRoomNumber)) {
        // 🚨 classroomID が有効かチェックする
        $stmtCheck = $dbh->prepare("SELECT COUNT(*) FROM classrooms WHERE classroomID = :classroomID");
        $stmtCheck->bindValue(":classroomID", intval($openRoomNumber), PDO::PARAM_INT);
        $stmtCheck->execute();
        $classroomExists = $stmtCheck->fetchColumn();

        if (!$classroomExists) {
            error_log("⚠️ 無効な classroomID: $openRoomNumber");
            $openRoomNumber = null; // 存在しない classroomID は NULL にする
        } else {
            $openRoomNumber = intval($openRoomNumber);
        }
    } else {
        $openRoomNumber = null;
    }

    // パスワードの処理
    $updatePassword = !empty($pass);
    if ($updatePassword) {
        $hashedPassword = hash('sha512', $pass);
    }

    // SQLクエリ（パスワード更新あり・なしで分岐）
    if ($updatePassword) {
        $sql = "UPDATE users 
                SET NAME = :name, MAIL = :mail, TEL = :tel, PERMISSION = :permission, 
                    PASS = :pass, USE_PERIOD = TO_TIMESTAMP(:use_period, 'YYYY-MM-DD HH24:MI'),
                    CLASSROOMID = :open_room_number
                WHERE USERID = :userid";
    } else {
        $sql = "UPDATE users 
                SET NAME = :name, MAIL = :mail, TEL = :tel, PERMISSION = :permission, 
                    USE_PERIOD = TO_TIMESTAMP(:use_period, 'YYYY-MM-DD HH24:MI'),
                    CLASSROOMID = :open_room_number
                WHERE USERID = :userid";
    }

    // クエリ準備
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
    $stmt->bindValue(":tel", $tel, PDO::PARAM_STR);
    $stmt->bindValue(":permission", intval($permission), PDO::PARAM_INT);
    $stmt->bindValue(":userid", intval($userId), PDO::PARAM_INT);
    $stmt->bindValue(":use_period", $usePeriod, PDO::PARAM_STR);

    // 🚨 classroomID が NULL の場合の対応
    if ($openRoomNumber !== null) {
        $stmt->bindValue(":open_room_number", $openRoomNumber, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(":open_room_number", null, PDO::PARAM_NULL);
    }

    // パスワードがある場合のみバインド
    if ($updatePassword) {
        $stmt->bindValue(":pass", $hashedPassword, PDO::PARAM_STR);
    }

    // SQLを実行
    $rexec = $stmt->execute();

    // 成功判定
    if ($rexec) {
        echo json_encode([
            'status' => 'success', 
            'name' => $name, 
            'mail' => $mail, 
            'tel' => $tel, 
            'use_period' => $usePeriod,
            'open_room_number' => $openRoomNumber
        ]);
    } else {
        error_log("SQL Error: " . implode(" | ", $stmt->errorInfo()));
        echo json_encode(['status' => 'error', 'message' => 'ユーザー情報の更新に失敗しました。']);
    }

} catch (PDOException $e) {
    // エラーハンドリング
    error_log('データベースエラー: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
