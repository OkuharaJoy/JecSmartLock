<?php
$name = $_POST['name'];
$id = $_POST['buildingID'];

// データベース接続
$dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
$dbuser = 'scott';
$dbpass = 'fh-p*KP2&C*QV#vdh4*2';

try {
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO CLASSROOM (name, BUILDINGID) VALUES (:NAME, :ID)";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":NAME", $name, PDO::PARAM_STR);
    $stmt->bindValue(":ID", $id, PDO::PARAM_INT);

    $rexec = $stmt->execute(); // SQL実行

    if ($rexec) {
        echo json_encode(['status' => 'success', 'name' => $name, 'id' => $id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ユーザーの登録に失敗しました。']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
