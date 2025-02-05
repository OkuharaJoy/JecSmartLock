<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = $_GET['action'] ?? 'lock';
$uid = isset($_GET['id']) ? intval($_GET['id']) : null;
$model_number = $_GET['code1'] ?? null;
$secret_key = $_GET['code2'] ?? null;

if (!$uid) {
    echo json_encode(["success" => false, "error" => "UIDが指定されていません。"]);
    exit;
}

if (!$model_number || !$secret_key) {
    echo json_encode(['error' => '必要なパラメータが不足しています。']);
    exit;
}

    if ($action === 'lock') {
        $open_close_history = 15;
    } elseif ($action === 'unlock') {
        $open_close_history = 14;
    } else {
        $open_close_history = 0;
    }


    $dsn = "oci:dbname=sesameT04atp01_low;charset=utf8";
    $username = "scott";
    $password = "fh-p*KP2&C*QV#vdh4*2";

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);


        $stmt = $pdo->prepare("
        SELECT keyid 
        FROM KEYS 
        WHERE MODEL_NUMBER = :model_number AND SECRET_KEY = :secret_key
        ");

        $stmt->execute([
            ':model_number' => $model_number, // MODEL_NUMBER の値
            ':secret_key' => $secret_key,     // SECRET_KEY の値
        ]);


        $keyid = $stmt->fetchColumn();

        if (!$keyid) {
            throw new Exception("指定されたモデル番号またはシークレットキーに一致する keyid が見つかりません。");
        }

        $stmt = $pdo->prepare("
        INSERT INTO KEYEVENT (OPEN_CLOSE_HISTORY, KEYID, USERID)
        VALUES (:opclhis, :keyid, :userid)
        ");

        $stmt->execute([
            ':opclhis' => $open_close_history,
            ':keyid' => $keyid,
            ':userid' =>$uid
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'データベースへの挿入が成功しました。',
        ]);

    } catch (PDOException $e) {
        // データベースエラーの処理
        echo json_encode([
            'success' => false,
            'error' => 'データベースエラー: ' . $e->getMessage(),
        ]);
    } catch (Exception $e) {
        // その他のエラー処理
        echo json_encode([
            'success' => false,
            'error' => 'エラー: ' . $e->getMessage(),
        ]);
    }
        ?>