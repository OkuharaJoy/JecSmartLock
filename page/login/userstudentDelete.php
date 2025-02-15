<?php
// DB接続設定
$dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
$dbuser = 'scott';
$dbpass = 'fh-p*KP2&C*QV#vdh4*2';

try {
    // PDOでDB接続
    $dbh = new PDO($dsn, $dbuser, $dbpass);

    // トランザクション開始
    $dbh->beginTransaction();

    // 利用期限と現在時刻を比較して、学生アカウントを対象に削除するSQL
    $check_sql = "
    SELECT USERID, 
           NAME, 
           TO_CHAR(USE_PERIOD, 'YYYY-MM-DD HH24:MI:SS') AS FORMATTED_DATE, 
           TO_CHAR(CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Tokyo', 'YYYY-MM-DD HH24:MI:SS') AS CURRENT_DATE
    FROM USERS 
    WHERE PERMISSION = 3
    ";

    // クエリ実行
    $stmt = $dbh->query($check_sql);

    $delete_count = 0;  // 削除対象のカウント

    // 結果を確認しながら処理
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $formatted_date = $row['FORMATTED_DATE'];  // 利用期限
        $current_date = $row['CURRENT_DATE'];      // 現在時刻（東京）

        // DateTimeオブジェクトで比較
        if ($formatted_date < $current_date) {
            // 削除処理
            $delete_sql = "DELETE FROM USERS WHERE USERID = :USERID";
            $delete_stmt = $dbh->prepare($delete_sql);
            $delete_stmt->bindParam(':USERID', $row['USERID'], PDO::PARAM_INT);
            $delete_stmt->execute();

            $delete_count++;  // 削除された件数をカウント
        }
    }

    // トランザクションのコミット
    $dbh->commit();

    // JSON形式で削除件数を返す
    echo json_encode(['deleted' => $delete_count]);

} catch (PDOException $e) {
    // エラーハンドリング
    echo json_encode(['error' => $e->getMessage()]);
}
?>
