<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // リクエストからユーザーIDを取得
    $userId = isset($_POST['userid']) ? intval($_POST['userid']) : null;

    if ($userId === null) {
        echo json_encode(['success' => false, 'message' => 'ユーザーIDが指定されていません。']);
        exit;
    }

    // データベース接続情報
    $dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
    $dbuser = 'scott';
    $dbpass = 'fh-p*KP2&C*QV#vdh4*2';

    try {
        // データベース接続
        $dbh = new PDO($dsn, $dbuser, $dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 削除クエリを準備
        $sql = "DELETE FROM users WHERE USERID = :userid";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);


        // クエリを実行
        $stmt->execute();

        // 成功レスポンスを返す
        echo json_encode(['success' => true, 'message' => 'ユーザーが削除されました。']);
    } catch (PDOException $e) {
        // エラーレスポンスを返す
        echo json_encode(['success' => false, 'message' => 'データベースエラー: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => '無効なリクエストです。']);
}
?>
