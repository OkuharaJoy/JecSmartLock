<?php
header('Content-Type: application/json');

// actionパラメータを取得
$action = $_GET['action'] ?? 'lock';
$devCode = isset($_GET['code1']) ? escapeshellarg($_GET['code1']) : null;
$secCode = isset($_GET['code2']) ? escapeshellarg($_GET['code2']) : null;

if (!$devCode) {
    echo json_encode(['error' => 'デバイスコードが指定されていません。']);
    exit;
}
if (!$secCode) {
    echo json_encode(['error' => 'シークレットコードが指定されていません。']);
    exit;
}

$script_path = "/var/www/htmls/venv/Scripts/DBSesame5lock.py";
$venv_path = "/var/www/htmls/venv/bin/activate";
$python_path = "/var/www/htmls/venv/bin/python3";


// actionに基づき実行するスクリプトを切り替える
if ($action === 'unlock') {
    $script_path = "/var/www/htmls/venv/Scripts/DBSesame5open.py"; // 解錠のスクリプト
}

$command = "source $venv_path && $python_path $script_path $devCode $secCode";
exec("$command 2>&1", $output, $return_var);

if ($return_var === 0) {
    echo json_encode([
        'success' => true,
        'message' => '操作が成功しました7',
        'output' => $output
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => '操作に失敗しました7',
        'output' => $output
    ]);
}
?>