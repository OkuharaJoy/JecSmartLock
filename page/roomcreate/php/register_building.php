<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ユーザーから送信されたデータを取得
    $newBuildingID = isset($_POST['new_building_id']) ? $_POST['new_building_id'] : null;
    $newBuildingName = isset($_POST['new_building_name']) ? $_POST['new_building_name'] : null;

    // 入力チェック（値が空でないか、0が不適切な値として扱われないか確認）
    if ($newBuildingID === null || $newBuildingName === null || $newBuildingID === '' || $newBuildingName === '') {
        echo "号館IDまたは号館名が入力されていません。";
        exit;
    }

    try {
        // データベース接続情報
        $dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
        $dbuser = 'scott';
        $dbpass = 'fh-p*KP2&C*QV#vdh4*2';
        $dbh = new PDO($dsn, $dbuser, $dbpass);

        // トランザクション開始
        $dbh->beginTransaction();

        // INSERT文の準備
        $insert_sql = "INSERT INTO CLASSROOM (BUILDINGID, CLASSROOMNAME) VALUES (:building_id, :classroomname)";
        $stmt = $dbh->prepare($insert_sql);

        // パラメータのバインド
        $stmt->bindParam(':building_id', $newBuildingID, PDO::PARAM_INT); // BUILDINGIDにバインド
        $stmt->bindParam(':classroomname', $newBuildingName, PDO::PARAM_STR); // CLASSROOMNAMEにバインド

        // クエリの実行
        $stmt->execute();

        // コミット（変更を確定）
        $dbh->commit();

        echo "部屋が正常に登録されました。";

    } catch (PDOException $e) {
        // エラーハンドリング（失敗時はロールバック）
        $dbh->rollBack();
        echo "エラー: " . $e->getMessage();
    }
}
?>
