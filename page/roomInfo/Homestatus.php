<?php
header("Content-Type: application/json");

// Pythonスクリプトと仮想環境のパス
$script_path = "/var/www/htmls/venv/Scripts/DBSesame5status.py";
$venv_path = "/var/www/htmls/venv/bin/activate";
$python_path = "/var/www/htmls/venv/bin/python3";

// デバイスコードの取得
$deviceCode = isset($_GET['code']) ? escapeshellarg($_GET['code']) : null;

if (!$deviceCode) {
    echo json_encode(['error' => 'デバイスコードが指定されていません。']);
    exit;
}

// Pythonスクリプトを実行するコマンド
$command = "source $venv_path && $python_path $script_path $deviceCode";

// Pythonスクリプトを実行し、出力を取得
exec($command, $output, $return_var);

if ($return_var !== 0) {
    echo json_encode([
        'success' => false,
        'error' => 'Pythonスクリプトの実行に失敗しました。',
        'output' => $output
    ]);
    exit;
}

// Pythonスクリプトの出力をパース
try {
    $batteryPercentage = floatval(rtrim($output[0]));
    $position = floatval(rtrim($output[1]));
    $status = rtrim($output[2]);
    $timestamp = rtrim($output[3]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Pythonスクリプトの出力をパースできませんでした。']);
    exit;
}

// データベース接続設定
$dsn = "oci:dbname=sesameT04atp01_low;charset=utf8";
$username = "scott";
$password = "fh-p*KP2&C*QV#vdh4*2";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // データベースにステータスを保存
    $stmt = $pdo->prepare("
        UPDATE KEYS
        SET 
            BATTERY_LEVEL = :battery,
            ANGLE = :position,
            CHAR_STATUS = :status,
            UPDATE_AT = SYSDATE + (9 / 24)
        WHERE MODEL_NUMBER = :device_code
    ");

    $stmt->execute([
        ':battery' => $batteryPercentage,
        ':position' => $position,
        ':status' => $status,
        ':device_code' => trim($deviceCode, "'")
    ]);


    $stmt = $pdo->prepare("SELECT BATTERY_LEVEL, ANGLE, CHAR_STATUS, UPDATE_AT FROM KEYS WHERE MODEL_NUMBER = :device_code");
    $stmt->execute([':device_code' => trim($deviceCode, "'")]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        // 成功レスポンス
        echo json_encode([
            'success' => true,
            'battery' => $data['BATTERY_LEVEL'],
            'position' => $data['ANGLE'],
            'status' => $data['CHAR_STATUS'],
            'timestamp' => $data['UPDATE_AT']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'データが見つかりません。']);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'データベースエラー: ' . $e->getMessage(),
    ]);
}
