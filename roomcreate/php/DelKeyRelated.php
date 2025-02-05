<?php
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $KcalID = isset($_POST['K_classroomID']) ? intval($_POST['K_classroomID']) : null;

        if ($KcalID === null) {
            echo json_encode(['success' => false, 'message' => 'roomIDが指定されていません。']);
            exit;
        }

        $dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
        $dbuser = 'scott';
        $dbpass = 'fh-p*KP2&C*QV#vdh4*2';
 
        try {
            // データベース接続
            $pdo = new PDO($dsn, $dbuser, $dbpass); // $dbh -> $pdo に変更
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT KEYID FROM KEYS WHERE CLASSROOMID = :classroomid");
            $stmt->execute([':classroomid' => $KcalID]);

            $keyid = $stmt->fetchColumn();
            if (!$keyid) {
                throw new Exception("指定されたclassroomidに一致する keyid が見つかりません。");
            }

            $stmt = $pdo->prepare("DELETE FROM KEYEVENT WHERE KEYID = :keyid");
            $stmt->execute([':keyid' => $keyid]);

            $stmt = $pdo->prepare("DELETE FROM KEYS WHERE KEYID = :keyid");
            $stmt->execute([':keyid' => $keyid]);

            // コミットして変更を確定
            $pdo->commit();

            echo json_encode(['success' => true, 'message' => '鍵関連情報が削除されました。']);
            exit;

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'データベースエラー: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '無効なリクエストです。']);
    }
?>












