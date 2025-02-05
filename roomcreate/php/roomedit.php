<?php
error_log('classroomid: ' . ($_POST['classroomid'] ?? 'NULL'));
error_log('id: ' . ($_POST['id'] ?? 'NULL'));
error_log('name: ' . ($_POST['name'] ?? 'NULL'));

header('Content-Type: application/json');

// データベース接続情報
$dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
$dbuser = 'scott';
$dbpass = 'fh-p*KP2&C*QV#vdh4*2';

try {
    $classroomID = $_POST['classroomid'] ?? null;
    $id = $_POST['id'];
    $name = $_POST['name'];

    // 必須フィールドの確認
    if (empty($classroomID) || empty($id) || empty($name)) {
        echo json_encode(['status' => 'error', 'message' => '必要なデータが不足しています。']);
        exit;
    }

    // データベース接続
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQLクエリの作成
    $sql = "UPDATE classroom 
            SET CLASSROOMNAME = :name, BUILDINGID = :id 
            WHERE CLASSROOMID = :classroomID";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":classroomID", $classroomID, PDO::PARAM_INT); // WHERE条件で使用
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);

    $rexec = $stmt->execute();

    if ($rexec) {
        echo json_encode(['status' => 'success', 'classroomid' => $classroomID, 'name' => $name, 'id' => $id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => '部屋情報の更新に失敗しました。']);
    }
} catch (PDOException $e) {
    // エラーをJSON形式で返す
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
