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

    $stmt = $pdo->prepare("SELECT MODEL_NUMBER FROM KEYS WHERE CLASSROOMID = :classroomid");
    $stmt->execute([':classroomid' => $classroomId]);
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($device) {
        echo json_encode(['code' => $device['MODEL_NUMBER']]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'デバイスコードが見つかりません。']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'データベースエラー: ' . $e->getMessage()]);
}
