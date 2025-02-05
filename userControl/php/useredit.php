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
    $pass = $_POST['password'] ?? '';  // パスワードがPOSTデータに存在するか確認
    $tel = $_POST['tel'] ?? '';
    $permission = $_POST['role'] ?? '';
    $usePeriod = $_POST['use_period'] ?? null; // use_period を受け取る

    // ユーザーIDが存在しない場合、エラーを返す
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'ユーザーIDが送信されていません']);
        exit;
    }

    // データベース接続と操作
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // use_periodの形式が正しいか確認
    if ($usePeriod) {
        // 'T'をスペースに置き換える（例：'2025-03-19T09:35' -> '2025-03-19 09:35'）
        $usePeriod = str_replace('T', ' ', $usePeriod);
    }

    // パスワードが送信されていれば、SHA-512でハッシュ化
    if (!empty($pass)) {
        // パスワードが送信されている場合は、受け取ったハッシュ値をそのまま使用
        $hashedPassword = $pass;  // JavaScript側でSHA-512ハッシュを作成して送信しているのでそのまま利用
        $sql = "UPDATE users 
                SET NAME = :name, MAIL = :mail, TEL = :tel, PERMISSION = :permission, PASS = :pass, USE_PERIOD = TO_TIMESTAMP(:use_period, 'YYYY-MM-DD HH24:MI')
                WHERE USERID = :userid";
    } else {
        // パスワードが送信されていなければ、パスワード更新をスキップ
        $hashedPassword = null;  // パスワードは更新しない
        $sql = "UPDATE users 
                SET NAME = :name, MAIL = :mail, TEL = :tel, PERMISSION = :permission, USE_PERIOD = TO_TIMESTAMP(:use_period, 'YYYY-MM-DD HH24:MI')
                WHERE USERID = :userid";
    }

    // ユーザー情報を更新
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":permission", $permission, PDO::PARAM_INT);
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
    $stmt->bindValue(":tel", $tel, PDO::PARAM_STR);
    $stmt->bindValue(":userid", $userId, PDO::PARAM_INT);
    $stmt->bindValue(":use_period", $usePeriod, PDO::PARAM_STR);  // use_period を追加

    // パスワード更新がある場合にのみ、パスワードを設定
    if ($hashedPassword !== null) {
        $stmt->bindValue(":pass", $hashedPassword, PDO::PARAM_STR);
    }

    // SQLを実行
    $rexec = $stmt->execute();

    if ($rexec) {
        echo json_encode(['status' => 'success', 'name' => $name, 'mail' => $mail, 'tel' => $tel, 'use_period' => $usePeriod]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ユーザー情報の更新に失敗しました。']);
    }

} catch (PDOException $e) {
    // エラーハンドリング
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
