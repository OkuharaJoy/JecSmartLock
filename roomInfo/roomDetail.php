<!DOCTYPE html>
<?php
session_start();
$user = $_POST['mail'] ?? ""; 
$pass = isset($_POST['pass']) ? hash('sha512', $_POST['pass']) : ""; // フォーム送信のname="pass"のデータを受け取り
$permission = $_POST['permission'] ?? ""; 
$userid = $_POST['userid'] ?? ""; 
$id = 0;
$err = "";
$login_user = "";
$login_user = "";
$permission = "";
$CLASSROOMID = $_GET['id'] ?? "";
$i=0;

try {
    function errorinfo(&$dbh) {
        $eary = $dbh->errorInfo();
        return '[' . $eary[0] . ':' . $eary[1] . ':' . $eary[2] . ']';
    }

    $dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
    $dbuser = 'scott';
    $dbpass = 'fh-p*KP2&C*QV#vdh4*2';
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $select_sql = "SELECT * FROM USERS WHERE MAIL= :mail AND PASS = :pass";
    $stmt = $dbh->prepare($select_sql);

    $rbind1 = $stmt->bindValue(":mail", $user, PDO::PARAM_STR);
    $rbind2 = $stmt->bindValue(":pass", $pass, PDO::PARAM_STR);
    if ($stmt->execute()) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($row = $stmt->fetch()) {
            $user_id = $row['USERID']; // USERIDを取得
                $user_permission = $row['PERMISSION'];
                $login_user = $row['MAIL']; // 行取得の果データからMAILキーのデータを取り出し
                            
                if (trim($login_user) == trim($user)) {
                    $_SESSION['id'] = $user_id;
                    $_SESSION['name'] = $user; // ユーザ名をセッションに保存
                    $_SESSION['pass'] = $pass;
                    $_SESSION['permission'] = $user_permission;
            } else {
                echo "失敗: {$login_user} {$user}";
            }
        }
        
    } else {
        $err .= errorinfo($dbh) . "=errorInfo()--><br><!--";
    }
    try {
        $select_sql = "SELECT KEYID, BATTERY_LEVEL, VOLTAGE, ANGLE, UPDATE_AT, CHAR_STATUS FROM  KEYS WHERE CLASSROOMID = :CLASSROOMID";
        $stmt = $dbh->prepare($select_sql);
        $stmt->bindValue(":CLASSROOMID", $CLASSROOMID, PDO::PARAM_INT);
        $stmt->execute(); // SQL実行
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $DBkey = [
                'keyid'    => $row['KEYID'],
                'battery'  => $row['BATTERY_LEVEL'],
                'voltage'  => $row['VOLTAGE'],
                'angle'    => $row['ANGLE'],
                'updateAt' => $row['UPDATE_AT'],
                'status'   => $row['CHAR_STATUS']
            ];

            $rawDate = $DBkey['updateAt'];
            $dateParts = explode(' ', $rawDate);

            // 月の名前を数値に変換（JAN => 01, FEB => 02...）
            $months = ['JAN' => '01', 'FEB' => '02', 'MAR' => '03', 'APR' => '04', 'MAY' => '05', 'JUN' => '06', 'JUL' => '07', 'AUG' => '08', 'SEP' => '09', 'OCT' => '10', 'NOV' => '11', 'DEC' => '12'];
            $month = $months[substr($dateParts[0], 3, 3)];

            // 日付の整形
            $formattedDate = '20' . substr($dateParts[0], -2) . '-' . $month . '-' . substr($dateParts[0], 0, 2) . ' ' . $dateParts[1];

            // DateTimeで整形
            $timestamp = new DateTime($formattedDate);
        } else {
            $DBkey = null;
            $timestamp = null;
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }

    



    $select_sql = "SELECT c.CLASSROOMID, c.CLASSROOMNAME, k.OPEN_CLOSE_STATUS, k.CHAR_STATUS, k.ANGLE, k.BATTERY_LEVEL, k.UPDATE_AT, ke.*, u.NAME
                    FROM  KEYS k
                    INNER JOIN CLASSROOM c on (c.CLASSROOMID = k.CLASSROOMID) 
                    INNER JOIN KEYEVENT ke on (ke.KEYID = k.KEYID) 
                    INNER JOIN USERS u on (ke.USERID = u.USERID)
                    WHERE c.CLASSROOMID = :CLASSROOMID
                    ORDER by ke.CREATED_AT desc";
    $stmt = $dbh->prepare($select_sql);
    $stmt->bindValue("CLASSROOMID", $CLASSROOMID, PDO::PARAM_INT);
    $rexec = $stmt->execute(); // SQL実行
    $key = array();
    while($row = $stmt->fetch()){
        $key['classroomId'] = $row['CLASSROOMID'];
        $key['classroomName'] = $row['CLASSROOMNAME'];
        $key['openCloseStatus'] = $row['OPEN_CLOSE_STATUS'];
        $key['charStatus'] = $row['CHAR_STATUS'];
        $key['ANGLE'] = $row['ANGLE'];
        $key['batteryLevel'] = $row['BATTERY_LEVEL'];
        $key['updateAt'] = $row['UPDATE_AT'];
        $key['keyEventId'] = $row['KEYEVENTID'];
        $key['openCloseHistry'] = $row['OPEN_CLOSE_HISTORY'];
        $key['createdAt'] = $row['CREATED_AT'];
        $key['userId'] = $row['USERID'];
        $key['userName'] = $row['NAME'];

        $input_date = $key['createdAt'];
        $date = DateTime::createFromFormat("d-M-y h.i.s.u A", $input_date);
        $date->modify("+9 hours");
        $key['formatted_date'] = $date->format("Y年n月j日G時i分s秒");

        $keys[$i++] = $key;

        
        
    }
    if ($key['openCloseStatus'] == 1) {
        $key['openCloseStatus'] = "開";
    }elseif($key['openCloseStatus'] == 2) {
        $key['openCloseStatus'] = "閉";
    }else{
        $key['openCloseStatus'] = "未登録";
    }

    function getResponse($openCloseHistory) {
        $responses = [
            0  => "none.",
            1  => "bleLock. セサミデバイスが施錠のBLEコマンドを受付ました。",
            2  => "bleUnLock. セサミデバイスが解錠のBLEコマンドを受付ました。",
            3  => "timeChanged. セサミデバイスの内部時計が校正された。",
            4  => "autoLockUpdated. オートロックの設定が変更されました。",
            5  => "mechSettingUpdated. 施解錠角度の設定が変更されました。",
            6  => "autoLock. セサミデバイスがオートロックしました。",
            7  => "manualLocked. 手動で施錠。",
            8  => "manualUnlocked. 手動で解錠。",
            9  => "manualElse. サムターンの動きあり。",
            10 => "driveLocked. モーターが確実に施錠しました。",
            11 => "driveUnlocked. モーターが確実に解錠しました。",
            12 => "driveFailed. モーターが施解錠の途中で失敗しました。",
            13 => "bleAdvParameterUpdated. BLEアドバタイシング設定変更。",
            14 => "wm2Lock. Wifiモジュールを経由して施錠しました。",
            15 => "wm2Unlock. Wifiモジュールを経由して解錠しました。",
            16 => "webLock. Web APIを経由して施錠しました。",
            17 => "webUnlock. Web APIを経由して解錠しました。"
        ];
        return $responses[$openCloseHistory] ?? "不明な値";
    }
        // getResponse($responses);
} catch (PDOException $e) {
    $err .= "catch(" . $e->getMessage() . ")";
}

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/room_detail.css">
    <title>room_detail Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="js/script.js"></script>
    <script>
        function formatTimestamp(timestamp) {
            // 日付の部分（15-JAN-25）と時間の部分（03.07.13.000000 AM）を分割
            const [datePart, timePart] = timestamp.split(" ");
            
            // 月名を対応する数字に変換
            const monthNames = {
                "JAN": "01", "FEB": "02", "MAR": "03", "APR": "04", "MAY": "05", "JUN": "06",
                "JUL": "07", "AUG": "08", "SEP": "09", "OCT": "10", "NOV": "11", "DEC": "12"
            };
            const [day, month, year] = datePart.split("-");
            const formattedMonth = monthNames[month.toUpperCase()];
            const formattedYear = `20${year}`; // 年を 20XX の形式に
            const formattedDate = `${formattedYear}-${formattedMonth}-${day}`;

            // 時間の部分を処理
            const [time, period] = timePart.split(" ");
            const [hours, minutes, seconds] = time.split("."); // 時間の部分を分割
            let formattedHours = parseInt(hours);
            
            // AM/PM の処理
            if (period === "PM" && formattedHours < 12) {
                formattedHours += 12; // PMの場合、12時間制から24時間制に変換
            } else if (period === "AM" && formattedHours === 12) {
                formattedHours = 0; // AM 12 時は 0 時として扱う
            }

            // フォーマットされた日時を文字列として返す
            const formattedTimestamp = `${formattedYear}-${formattedMonth}-${day}T${String(formattedHours).padStart(2, '0')}:${minutes}:${seconds}`;
            const dateObj = new Date(formattedTimestamp);

            // 無効な日時かどうかをチェック
            if (isNaN(dateObj)) {
                console.error("無効な日時:", timestamp);
                return "無効な日時";
            }
            
            // フォーマットを行い、返却
            const yearOut = dateObj.getFullYear();
            const monthOut = dateObj.getMonth() + 1;
            const dayOut = dateObj.getDate();
            const hoursOut = dateObj.getHours();
            const minutesOut = dateObj.getMinutes();
            const secondsOut = dateObj.getSeconds();

            return `${yearOut}年${monthOut}月${dayOut}日 ${hoursOut}時${minutesOut}分${secondsOut}秒`;
        }

        document.getElementById('updateButton').addEventListener('click', async function() {
    const classroomId = 'your_classroom_id'; // 適切な教室IDを設定
    await fetchDeviceStatus(classroomId);  // 状態を更新
    location.reload();  // 状態更新後にページをリロード
});

async function fetchDeviceStatus(classroomId) {
    try {
        const response = await fetch(`109status.php?classroomid=${classroomId}`);
        if (!response.ok) {
            throw new Error(`HTTPエラー: ${response.status}`);
        }

        const { code } = await response.json(); // 109status.php が返すデバイスコード
        if (!code) {
            throw new Error("デバイスコードの取得に失敗しました。");
        }

        // 次に Homestatus.php を呼び出す
        const homestatusResponse = await fetch(`Homestatus.php?code=${code}`);
        if (!homestatusResponse.ok) {
            throw new Error(`Homestatus.php の呼び出しに失敗しました: ${homestatusResponse.status}`);
        }

        const statusData = await homestatusResponse.json(); // ステータスデータの取得
        console.log(statusData); // ステータスデータを確認

        displayStatus(statusData);

        

    } catch (error) {
        console.error("エラーが発生しました:", error.message);
    }
}

function displayStatus(statusData) {
    if (statusData.success) {
        document.getElementById("battery").textContent = `バッテリー残量: ${statusData.battery}`;
        document.getElementById("position").textContent = `ANGLE: ${statusData.position}`;
        document.getElementById("status").textContent = `ステータス: ${statusData.status}`;
        document.getElementById("timestamp").textContent = `最終更新時間: ${formatTimestamp(statusData.timestamp)}`;
    } else {
        document.getElementById("status").textContent = `Error: ${statusData.error}`;
    }
}

async function controlDoor(action, classroomId, id) {
    try {
        // KeyDB.phpからデータを取得
        const response = await fetch(`KeyDB.php?classroomid=${classroomId}`);
        if (!response.ok) {
            throw new Error(`KeyDB.php の呼び出しに失敗しました: ${response.statusText}`);
        }
        const { codes } = await response.json(); // サーバーから2つのコードを取得
        if (!codes || codes.length !== 2) {
            throw new Error("2つのコードの取得に失敗しました。");
        }
        const [code1, code2] = codes;
        // Keymove.phpにデータを送信
        const keymoveResponse = await fetch(`Keymove.php?action=${action}&code1=${code1}&code2=${code2}`, {
            method: "GET",
        });
        const result = await keymoveResponse.json();
        if (result.success) {
            console.log("操作成功:", result.message);
            console.log("id:", id);
            console.log("action:", action);
            console.log("code1:", code1);
            console.log("code2:", code2);
            setTimeout(() => {
                fetchDeviceStatus(classroomId);
            }, 4500); 
            DBHistory(id, action, code1, code2);
        } else {
            alert("操作失敗: " + result.error);
        }
    } catch (error) {
        alert("通信エラー: " + error.message);
    }
}


     

        function DBHistory(id, action, code1, code2) {
            // fetchを使ってPHPファイルにリクエストを送る
            fetch(`DBHistory.php?id=${id}&action=${action}&code1=${code1}&code2=${code2}`)
                .then(response => {
                    // レスポンスが正常か確認
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    // レスポンスをJSONとして取得
                    return response.json();
                })
                .then(data => {
                    // 取得したデータを処理
                    if (data.success) {
                        console.log("操作成功:", data.message); 
                        setTimeout(() => {
                        location.reload();
                    }, 9000);  
                    } else {
                        alert("操作失敗: " + data.error);  // エラーメッセージを表示
                    }
                })
                .catch(error => {
                    // エラー発生時にコンソールにエラーメッセージを表示
                    console.error('Error:', error);
                    alert("通信エラー: " + error.message);
                });
        } 
    </script>

</head>

<body>
    <header>
        <h1>JEC SMART LOCK</h1>
    </header>
    <?php if (isset($_SESSION['name']) && $_SESSION['name'] != "") { ?> 
        <div class="name">ようこそ、<?= "{$_SESSION['name']}" ?>さん</div>
        <!-- id確認 -->
        <!-- <div><　?= "{$_SESSION['permission']}" ?></div>
        <div><　?= "{$_SESSION['name']}" ?></div>
        <div><　?= "{$_SESSION['id']}" ?></div> -->
        <img src="img/日本電子.png" alt="メニュー" class="menu-icon" id="menuIcon">
        <div class="sidebar">
            <ul>
                <li><a href="#"><img src="img/日本電子.png" alt="ホーム" class="icon"></a></li>
                <li><a href="../home/home.php">ホーム</a></li>
                <?php
                    $permission = $_SESSION['permission'] ?? 3;
                    if ($permission == 1): ?>
                        <li><a href="../roomcreate/roomcreate.php">教室管理</a></li>
                    <?php endif; ?>
                    <li><a href="../userControl/UserControl.php">ユーザー管理</a></li>
                    <li><a href="../login/login.php">ログアウト</a></li>
            </ul>
        </div>

        <main>
            <div class="content">
                <div class="container">
                    <div class="classroom-status">
                    <span class="status-icon" 
                        style="background-color: <?= $key['charStatus'] === 'locked' ? 'green' : ($key['charStatus'] === 'unlocked' ? 'red' : 'gray'); ?>">
                    </span>


                        <div class="status-details">
                            <h2><?= $key['classroomName'] ?>号室</h2>
                            <p>現在の状況: <strong><?php
                                if ("charStatus" == "開") {
                                    
                                } elseif ("charStatus" == "閉") {
                                    
                                } else {
                                    echo $key['charStatus']; // 他の値があればそのまま表示
                                }
                            ?></strong></p>
                        </div>
                    </div>

                    <div class="status-container">
                        <!-- <p>CLASSROOMIDの値: <strong>< ?php echo htmlspecialchars($CLASSROOMID); ?></strong></p> -->
                        <p id="battery">バッテリー残量: <?= htmlspecialchars($DBkey['battery'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p id="position">ANGLE: <?= htmlspecialchars($DBkey['angle'], ENT_QUOTES, 'UTF-8') ?></p>
                        <!-- <p id="status">Status: <　?= $key['openCloseStatus'] ?></p> -->
                        <p id="status">ステータス: <?= htmlspecialchars($DBkey['status'], ENT_QUOTES, 'UTF-8') ?></p>
                        <!-- <P id="voltage">電圧: <?= htmlspecialchars($DBkey['voltage'], ENT_QUOTES, 'UTF-8') ?></p> -->
                        <p id="timestamp">最終更新時間: <?= $timestamp ? $timestamp->format('Y年m月d日 H時i分s秒') : 'データがありません' ?></p>
                        <button id="updateButton" onclick="fetchDeviceStatus(<?= htmlspecialchars($CLASSROOMID) ?>)">更新</button>
                        <!-- <button onclick="handleButtonClick()">こうしん</button> -->
                    </div>

                    <h3>日付を選択してください</h3>
                    <!-- <form id="dateForm" method="POST">
                        <label for="start_date">開始日:</label>
                        <input type="date" id="start_date" name="start_date" value="<?= isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : '' ?>" onchange="submitForm()">
                    </form> -->
                        <!-- < ?php
                        // フォームが送信されていれば、選ばれた日付を取得
                        if (isset($_POST['start_date'])) {
                            $start_date = $_POST['start_date'];
                        
                            echo "<p>開始日: " . htmlspecialchars($start_date) . "</p>";
                        
                            // ここで日付に基づくデータ取得処理を行う
                            // 例えば、データベースから開始日以降のデータを取得する例です。
                            // 以下の例では、$keysのデータが開始日より後のものだけをフィルタリングする例です。
                            
                            // データフィルタリング（仮の例）
                            $filtered_keys = array_filter($keys, function($key) use ($start_date) {
                                // 日付を比較してフィルタリング（$key['formatted_date']を日付として比較）
                                return $key['formatted_date'] >= $start_date;
                            });
                        }
                        ?> -->
                    <button id="toggleDoor" class="door-toggle-button">
                        <?php
                            $status = $DBkey['status'] ?? 'unlocked';
                            if ($status === "unlocked") {
                                echo "かぎを閉める";
                            } elseif ($status === "locked") {
                                echo "かぎを開ける";
                            } else {
                                echo "扉の開閉"; // 他の状態の場合のデフォルト
                            }
                        ?>
                    </button>

                    <div id="overlay" class="overlay">
                        <div class="overlay-content">
                            <h3><?= $key['classroomName'] ?>号室を開けますか</h3>
                            <button id="closeOverlay" class="close-button">閉じる</button>
                            <!-- OPEN_CLOSE_STATUS に応じてボタンのテキストを変更 -->
                            <?php 
                                if ($status === 'unlocked'): ?>
                                    <button id="confirmButton" class="confirm-button" onclick="controlDoor('lock', <?= htmlspecialchars($CLASSROOMID) ?>, <?= htmlspecialchars($_SESSION['id'], ENT_QUOTES, 'UTF-8') ?>)">
                                        閉める
                                    </button>
                            <?php
                                $permission = $_SESSION['permission'] ?? 3;
                                elseif ($status === 'locked' && $permission != 3): ?>
                                    <button id="confirmButton" class="confirm-button" onclick="controlDoor('unlock', <?= htmlspecialchars($CLASSROOMID) ?>, <?= htmlspecialchars($_SESSION['id'], ENT_QUOTES, 'UTF-8') ?>)">
                                        開ける
                                    </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="log--container-wrapper">
                        <table class="log-container">
                            <thead>
                                <tr>
                                    <th>開閉日時</th>
                                    <th>開閉判定</th>
                                    <th>開閉者氏名</th>
                                </tr>
                            </thead>
                            <tbody id="logTableBody" class="log-table-body">
                                <?php 
                                if (isset($filtered_keys)) {
                                    foreach ($filtered_keys as $key) { ?>
                                        <tr class="history">
                                            <td><p id="timehistory"><?= $key['formatted_date'] ?></p></td>
                                            <td><p id="message"><?= getResponse($key['openCloseHistry']) ?></p></td>
                                            <td><?= $key['userName'] ?></td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    <?php } else { ?>
    <div class="login-failed">
        <h2>ログインできませんでした</h2>
        
        <?php if (!empty($err)) { ?>
            <div class="error-message">
                <p>エラー内容:</p>
                <p><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p> <!-- エラーメッセージを安全に表示 -->
            </div>
        <?php } else { ?>
            <p>メールアドレスまたはパスワードが正しくありません。</p>
        <?php } ?>
        <a href="../login/login.php" class="back-to-login">ログイン画面へ戻る</a>
    </div>
<?php } ?>
</body>
</html>