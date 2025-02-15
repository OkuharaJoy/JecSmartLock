<!DOCTYPE html>
<?php
session_start();
// $_SESSION = array();
$user = $_POST['mail'] ?? "";  // フォーム送信のname="user"のデータを受け取り
$pass = isset($_POST['pass']) ? hash('sha512', $_POST['pass']) : ""; // フォーム送信のname="pass"のデータを受け取り
$id = 0;
$err = "";
$login_user = "";
$permission = "";
$user_id = "";
$classrooms = [];
$status = $_SESSION['OPEN_CLOSE_STATUS'] ?? 0; // セッションからステータスを取得、なければデフォルト値0を使用
try {
    function errorinfo(&$dbh)
    {
        $eary = $dbh->errorInfo();
        return '[' . $eary[0] . ':' . $eary[1] . ':' . $eary[2] . ']';
    }

    $dsn = 'oci:dbname=sesameT04atp01_low;charset=utf8';
    $dbuser = 'scott';
    $dbpass = 'fh-p*KP2&C*QV#vdh4*2';
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $login_sql = "SELECT * FROM USERS WHERE MAIL= :mail AND PASS = :pass";
    $stmt = $dbh->prepare($login_sql);

    $rbind1 = $stmt->bindValue(":mail", $user, PDO::PARAM_STR);
    $rbind2 = $stmt->bindValue(":pass", $pass, PDO::PARAM_STR);
    if ($stmt->execute()) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($row = $stmt->fetch()) {
            $user_id = $row['USERID']; // USERIDを取得
            $login_user = $row['MAIL']; // 行取得の果データからMAILキーのデータを取り出し
            $user_permission = $row['PERMISSION'];
            $classroom_id = $row['CLASSROOMID'];

            if (trim($login_user) == trim($user)) {
                $_SESSION['id'] = $user_id;
                $_SESSION['name'] = $user; // ユーザ名をセッションに保存
                $_SESSION['pass'] = $pass;
                $_SESSION['permission'] = $user_permission;
            }
        }
    } else {
        $err .= errorinfo($dbh) . "=errorInfo()--><br><!--";
    }
    // 前回ログイン
    // if ($id != 0) {
    //     $insert_sql = 'INSERT INTO logins VALUES (loginid.NEXTVAL, :id, SYSDATE, NULL)'; // ログイン記録のlogins表に記録するinsert文
    //     $stmt = $dbh->prepare($insert_sql);
    //     $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    //     $stmt->execute(); // sql実行
    //     $lines = $stmt->rowCount(); // 直近のSQL文によって作用した行数の取得
    //     if ($lines != 1) {
    //         $err = "ログ書き込みエラー";
    //     }
    // }

    // 全教室表示
    $select_sql = 'SELECT c.CLASSROOMID, c.CLASSROOMNAME, c.BUILDINGID, k.OPEN_CLOSE_STATUS, k.CHAR_STATUS
                    FROM CLASSROOM c
                    LEFT JOIN KEYS k ON c.CLASSROOMID = k.CLASSROOMID
                    WHERE c.CLASSROOMID BETWEEN 1 AND 109
                    order by c.CLASSROOMID';
    $err = "";
    try {
        $stmt = $dbh->prepare($select_sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $CLASSROOM = [];
            $CLASSROOM['classroomID'] = $row['CLASSROOMID'];
            $CLASSROOM['id'] = $row['BUILDINGID'];
            $CLASSROOM['name'] = $row['CLASSROOMNAME'];
            $CLASSROOM['status'] = $row['OPEN_CLOSE_STATUS'];
            $CLASSROOM['charStatus'] = $row['CHAR_STATUS'];
            $classrooms[] = $CLASSROOM;
        }
    } catch (PDOException $e) {
        // phpinfo();
        $err .= '接続に失敗しました: ' . $e->getMessage();
    }
    //最近使用した教室
    try {
        $recently_sql = 'SELECT DISTINCT c.CLASSROOMNAME, c.CLASSROOMID, k.CHAR_STATUS
                        FROM CLASSROOM c 
                        LEFT JOIN KEYS k ON c.CLASSROOMID = k.CLASSROOMID
                        LEFT JOIN KEYEVENT ke ON k.KEYID = ke.KEYID
                        LEFT JOIN USERS u ON u.USERID = ke.USERID
                        WHERE u.USERID = :user_id';

        $stmt = $dbh->prepare($recently_sql);
        $stmt->bindValue(":user_id", $_SESSION['id'], PDO::PARAM_INT);
        $err = "";
        $stmt->execute();
        $recentlys = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $RECENTLY = [];
            $RECENTLY['classroomId'] = $row['CLASSROOMID'];
            $RECENTLY['classroomName'] = $row['CLASSROOMNAME'];
            $RECENTLY['charStatus'] = $row['CHAR_STATUS'];
            $recentlys[] = $RECENTLY;
        }
    } catch (PDOException $e) {
        // phpinfo();
        $err .= '接続に失敗しました: ' . $e->getMessage();
    }
    //学生アカウント用SQL
    try {
        $student_sql = 'SELECT c.CLASSROOMNAME, c.CLASSROOMID, k.CHAR_STATUS
                        FROM CLASSROOM c 
                        LEFT JOIN KEYS k ON c.CLASSROOMID = k.CLASSROOMID
                        LEFT JOIN USERS u ON c.CLASSROOMID = u.CLASSROOMID
                        WHERE u.USERID = :user_id';

        $stmt = $dbh->prepare($student_sql);
        $stmt->bindValue(":user_id", $_SESSION['id'], PDO::PARAM_INT);
        $err = "";
        $stmt->execute();
        $students = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $STUDENT = [];
            $STUDENT['classroomId'] = $row['CLASSROOMID'];
            $STUDENT['classroomName'] = $row['CLASSROOMNAME'];
            $STUDENT['charStatus'] = $row['CHAR_STATUS'];
            $students[] = $STUDENT;
        }
    } catch (PDOException $e) {
        // phpinfo();
        $err .= '接続に失敗しました: ' . $e->getMessage();
    }
    $stmt = null;
    $dbh = null; // PDOオブジェクトにnullを代入してPDOとの接続を解除
} catch (PDOException $e) {
    $err .= "catch(" . $e->getMessage() . ")";
}
?>


<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>homePage</title>
    <link rel="stylesheet" href="css/room.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
    <script>

    </script>
</head>

<body>
    <?php if (isset($_SESSION['name']) && $_SESSION['name'] != "") { ?>
        <header>
            <h1>JEC SMART LOCK</h1>
        </header>
        <div class="aaaa">
            <div class="header-container">
                <div class="name">ようこそ、<?= "{$_SESSION['name']}" ?>さん</div>
                <img src="img/日本電子.png" alt="メニュー" class="menu-icon" id="menuIcon">
            </div>
            <div class="sidebar">
                <ul>
                    <li><a href="#"><img src="img/日本電子.png" alt="ホーム" class="icon"></a></li>
                    <li><a href="../home/home.php">ホーム</a></li>
                    <?php
                    $permission = $_SESSION['permission'] ?? 3;
                    if ($permission == 1) { ?>
                        <li><a href="../roomcreate/roomcreate.php">教室管理</a></li>
                        <li><a href="../userControl/UserControl.php">ユーザー管理</a></li>
                    <?php } elseif ($permission == 2) { ?>
                        <li><a href="../userControl/UserControl.php">ユーザー管理</a></li>
                    <?php } ?>
                    <li><a href="../login/login.php">ログアウト</a></li>
                    <li><span class="close-button"></span></li>

                </ul>
            </div>
            <main>
                <div class="content">
                    <div class="container">
                        <div class="header">開閉状況</div>
                        <div class="toggle-container">
                            <span>全ての号室表示・非表示</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="toggleCheckbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="status-explanation">
                            <ul>
                                <li><span class="dot dot-open">●</span> → <strong>閉</strong></li>
                                <li><span class="dot dot-closed">●</span> → <strong>開</strong></li>
                                <li><span class="dot dot-unregistered">●</span> → <strong>未登録</strong></li>
                            </ul>
                        </div>
                        <!-- 学生アカウント施錠教室表示 -->
                        <?php
                        if ($permission == 3) { ?>
                        <div class="building-toggle" data-classroom-id="<?= $STUDENT['classroomId'] ?>"
                            onclick="toggleRooms<?= $STUDENT['classroomId'] ?>()">施錠可能教室
                        </div>
                        <p><?= $STUDENT['charStatus']; ?></p>
                        <div class="rooms-buttons">
                            <a href="../roomInfo/roomDetail.php?id=<?= $STUDENT['classroomId'] ?>">
                                <button title="" class="room-practice
                        <?php

                        $charStatus = trim($STUDENT['charStatus'] ?? '');
                        if (is_null($STUDENT['charStatus'])) {
                            echo 'status-unregistered';
                        } elseif ($STUDENT['charStatus'] === 'unlocked') {
                            echo 'status-open';
                        } elseif ($STUDENT['charStatus'] === 'locked') {
                            echo 'status-closed';
                        }
                        ?>">
                                    <span class="classroom"> 841 <!-- < ?= $RECENTLY['classroomName'] ?>  --> </span>
                                </button>
                            </a>
                        </div>
                        <?php } else { ?>
                        <!-- 最近使用した教室 -->
                        <div class="building-section">
                            <div class="building-toggle" data-classroom-id="<?= $RECENTLY['classroomId'] ?>"
                                onclick="toggleRooms<?= $RECENTLY['classroomId'] ?>()">最近使用した教室
                            </div>
                            <!-- <img src="img/building0.png" alt="メニュー" class="menu-icon1" id="menuIcon"> -->
                            <?php foreach ($recentlys as $RECENTLY) { ?>
                            <div class="rooms-buttons">
                                <a href="../roomInfo/roomDetail.php?id=<?= $RECENTLY['classroomId'] ?>">
                                    <button title="" class="room-practice
                                    <?php
                                    $charStatus = trim($RECENTLY['charStatus'] ?? '');
                                    if (is_null($RECENTLY['charStatus'])) {
                                        echo 'status-unregistered';
                                    } elseif ($RECENTLY['charStatus'] === 'unlocked') {
                                        echo 'status-open';
                                    } elseif ($RECENTLY['charStatus'] === 'locked') {
                                        echo 'status-closed';
                                    }
                                    ?>">
                                        <span class="classroom"><?= $RECENTLY['classroomName'] ?> </span>
                                    </button>
                                </a>
                            </div>
                        <!-- </div> -->
                        <?php } ?>
                        <!-- 動的 -->
                        <?php $i = 0;
                        $id = 0; ?>
                        <?php foreach ($classrooms as $CLASSROOM) { ?>
                            <?php if ($id != $CLASSROOM['id']) {
                                if ($id != 0) { ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                        <div class="building-section">
                            <div class="building-toggle" data-classroom-id="<?= $CLASSROOM['id'] ?>"
                                onclick="toggleRooms<?= $CLASSROOM['id'] ?>()"><?= $CLASSROOM['id'] ?>号館
                            </div>
                            <div class="rooms" id="rooms-container<?= $CLASSROOM['id'] ?>">
                                <div class="image-and-rooms">
                                    <img src="img/building<?= $CLASSROOM['id'] ?>.png" alt="<?= $CLASSROOM['id'] ?>">
                                    <div class="rooms-buttons">
                            <?php } ?>
                                        <a href="../roomInfo/roomDetail.php?id=<?= $CLASSROOM['classroomID'] ?>">
                                            <button title="" class="room-practice <?php
                                                $charStatus = trim($CLASSROOM['charStatus'] ?? '');
                                                if (is_null($CLASSROOM['charStatus'])) {
                                                    echo 'd-none';
                                                } elseif ($CLASSROOM['charStatus'] === 'unused') {
                                                    echo 'status-unregistered';
                                                } elseif ($CLASSROOM['charStatus'] === 'unlocked') {
                                                    echo 'status-open';
                                                } elseif ($CLASSROOM['charStatus'] === 'locked') {
                                                    echo 'status-closed';
                                                }
                                                ?>">
                                                <span class="classroom"><?= $CLASSROOM['name'] ?></span>
                                            </button>
                                            <style>
                                                .status-unregistered {
                                                    background-color: #FFF3CD;
                                                }

                                                /* 柔らかい黄色 */
                                                .status-open {
                                                    background-color: #F8D7DA;
                                                }

                                                /* 柔らかい赤 */
                                                .status-closed {
                                                    background-color: #D1ECF1;
                                                }

                                                /* 柔らかい青 */
                                                .d-none {
                                                    display: none;
                                                }

                                                /* 非表示 */
                                            </style>
                                        </a>
                                        <?php $id = $CLASSROOM['id']; ?>
                                        <?php
                                        // ここで$CLASSROOM['OPEN_CLOSE_STATUS']が1、2などの値を持っていると仮定します。
                                        // 例えば、セッションからステータスを取得するか、データベースから直接取得するなど
                            
                                        $status = $CLASSROOM['OPEN_CLOSE_STATUS'] ?? 0; // ここでステータスを取得、デフォルトは0（不明）
                            
                                        // ステータスに応じて背景色のクラスを設定
                                        $statusClass = '';
                                        if ($status == 1) {
                                            $statusClass = 'open'; // 開放中（例: 緑）
                                        } elseif ($status == 2) {
                                            $statusClass = 'closed'; // 施錠中（例: 赤）
                                        } else {
                                            $statusClass = 'default'; // その他（例: 灰色）
                                        }
                                        ?>
                                    <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </main>
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
<?= "<!-- user= 【${user}】<br>, pass=【${pass}】--><br><br>\n" ?>
<?= "<!-- Err:err=【${err}】--><br>\n" ?>

</html>