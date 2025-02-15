<?php
// データベース接続情報を設定
$dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
$dbuser = 'scott';
$dbpass = 'fh-p*KP2&C*QV#vdh4*2';

// PDOインスタンスを作成して接続
try {
    $pdo = new PDO($dsn, $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // エラーモード設定
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// 日付パラメータを取得
$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';

// DBから履歴を取得するSQL（例）
if ($selectedDate) {
    $sql = "SELECT * FROM history WHERE DATE(formatted_date) = :selectedDate ORDER BY formatted_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':selectedDate', $selectedDate);
    $stmt->execute();
    $keys = $stmt->fetchAll();
} else {
    // デフォルトの履歴を表示（全履歴）
    $sql = "SELECT * FROM history ORDER BY formatted_date DESC";
    $stmt = $pdo->query($sql);
    $keys = $stmt->fetchAll();
}
?>
