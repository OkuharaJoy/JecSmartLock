<?php
header("Content-Type: application/json");
$dsn = "oci:dbname=sesameT04atp01_low;charset=utf8";
$username = "scott";
$password = "fh-p*KP2&C*QV#vdh4*2";

$classroomId = isset($_GET['classroomid']) ? intval($_GET['classroomid']) : 0;

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // デバイスコードとシークレットコードを取得するクエリ
    $stmt = $pdo->prepare("SELECT MODEL_NUMBER, SECRET_KEY FROM KEYS WHERE CLASSROOMID = :classroomid");
    $stmt->execute([':classroomid' => $classroomId]);
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($device) {
        echo json_encode([
            'success' => true,
            'codes' => [$device['MODEL_NUMBER'], $device['SECRET_KEY']]
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'デバイスコードまたはシークレットコードが見つかりません。'
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'データベースエラー: ' . $e->getMessage()
    ]);
}