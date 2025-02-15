<!DOCTYPE html>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user = $_POST['mail'] ?? "";
$pass = isset($_POST['pass']) ? hash('sha512', $_POST['pass']) : "";
$id = 0;
$err = "";
$login_user = "";
$classrooms = [];
$buildings = [];
$status = $_SESSION['OPEN_CLOSE_STATUS'] ?? 0;

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

    $stmt->bindValue(":mail", $user, PDO::PARAM_STR);
    $stmt->bindValue(":pass", $pass, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($row = $stmt->fetch()) {
            $login_user = $row['MAIL'];
            if (trim($login_user) == trim($user)) {
                $_SESSION['name'] = $user;
                $id = $row['USERID'];
            }
        }
    } else {
        $err .= errorinfo($dbh) . "=errorInfo()--><br><!--";
    }

    $classroom_sql = "SELECT c.CLASSROOMID, c.CLASSROOMNAME, c.BUILDINGID, k.MODEL_NUMBER, k.SECRET_KEY 
                        FROM CLASSROOM c
                        LEFT JOIN KEYS k ON (c.CLASSROOMID = k.CLASSROOMID)
                        ORDER BY c.BUILDINGID, 
                            CASE 
                                WHEN REGEXP_LIKE(c.CLASSROOMNAME, '[A-Za-z]') THEN 0 
                            ELSE 1 
                        END,
                        c.CLASSROOMNAME"; 
    
    try {
        $stmt = $dbh->prepare($classroom_sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $CLASSROOM = [
                'classroomID' => $row['CLASSROOMID'],
                'name' => $row['CLASSROOMNAME'],
                'id' => $row['BUILDINGID'],
                'MODEL_NUMBER' => $row['MODEL_NUMBER'],
                'SECRET_KEY' => $row['SECRET_KEY']
            ];
            $classrooms[] = $CLASSROOM;
        }
    } catch (PDOException $e) {
        $err .= '接続に失敗しました: ' . $e->getMessage();
    }

    $BUILDING_sql = 'SELECT BUILDINGID, NAME, ADDRESS FROM BUILDING';
    try {
        $stmt = $dbh->prepare($BUILDING_sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $BUILDING = [
                'buildingid' => $row['BUILDINGID'],
                'buildingname' => $row['NAME'],
                'address' => $row['ADDRESS']
            ];
            $buildings[] = $BUILDING;
        }
    } catch (PDOException $e) {
        $err .= '接続に失敗しました: ' . $e->getMessage();
    }

    $stmt = null;
    $dbh = null;
} catch (PDOException $e) {
    $err .= "catch(" . $e->getMessage() . ")";
}

// print_r($classrooms);
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>roomCreate</title>
    <link rel="stylesheet" href="css/roomcreate.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/roomcreate.js"></script>
    <script src="js/roomDelete.js"></script>
    <script src="js/keyCreate.js"></script>
    <script src="js/DeleteKeyRelated.js"></script>
</head>
<body>
<?php if (isset($_SESSION['name']) && $_SESSION['name'] != "") { ?> 
    <header>
        <h1>JEC SMART LOCK</h1>
    </header>
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
                <h2>教室管理</h2>
                <div class="tag">
                    <input type="text" id="searchQuery" class="search-box" placeholder="教室番号検索" onkeyup="filterTable()">
                    <div id="overlay" onclick="closeCreateModal('create_user1')"></div>
                    <p class="open-create_user" onclick="openCreateModal('create_user1')"><span>新規教室登録</span></p>
<!-- 新規教室登録 -->
                    <div class="create_user" id="create_user1" style="display:none;">
                        <div class="model_title">
                            <form action="php/register_building.php" method="post">
                                <h3>新規教室登録</h3>
                                <select id="new_building_id" name="new_building_id" required>
                                    <option value="">選択してください</option>
                                    <?php
                                    // 号館IDの選択肢を表示
                                    foreach ($buildings as $building) {
                                        echo "<option value='" . htmlspecialchars($building['buildingid']) . "'>" . htmlspecialchars($building['buildingname']) . "</option>";
                                    }
                                    ?>
                                </select>
                                <label for="new_building_name">号館名:</label>
                                <input type="text" id="new_building_name" name="new_building_name" placeholder="号館名を入力" required>
                                <div class="button-container">
                                    <button class="close-btn" type="button" onclick="closeCreateModal()">閉じる</button>
                                    <button class="register-btn" id="createRoom" type="submit">登録</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
<!-- 編集 -->
                <p class="open-edit_user" onclick="openModal('edit_user1')"></p>
                <div class="edit_user" id="edit_user1"  style="display: none; opacity: 0;">
                    <form>
                        <h3>教室編集</h3>
                        <input type="hidden" id="edit-room">

                        <label for="building_name">号館:</label>
                        <input type="text" id="building_name" name="building_name" required>

                        <label for="room_number">教室番号:</label>
                        <input type="text" id="room_number" name="room_number" required>

                        <div class="button-container1">
                            <button class="close-btn" type="button" onclick="closeModal('edit_user1')">閉じる</button>
                            <button class="update-btn" id="editRoom" type="button">更新</button>
                        </div>
                    </form>
                </div>
<!-- 削除 -->
                <p class="open-edit_user" onclick="openModal('deleteKey')"></p>
                <div class="edit_user" id="deleteKey" style="display:none;">
                    <form>
                        <h3>この教室を削除しますか？</h3>
                        <label for="">号館の名前</label>
                        <label for="">教室の名前</label>
                        <div class="button-container">
                            <button class="close-btn" type="button" onclick="closeModal('deleteKey')">閉じる</button>
                            <button class="delete-btn" type="submit">削除する</button>
                        </div>
                    </form>
                </div>
<!-- 鍵登録 -->
                <div id="overlay" onclick="closeCreateModalKey('create_key')"></div>
                <div class="create_user" id="create_key" style="display:none;">
                    <div class="model_title">
                    <form method="POST" enctype="multipart/form-data">
                        <h3>鍵デバイス更新</h3>
                        <input type="hidden" id="inclassroomid">

                        <label for="model_number">デバイスUUID:</label>
                        <input type="text" id="model_number" name="model_number" required>

                        <label for="secret_key">デバイスシークレットキー:</label>
                        <div style="position: relative; display: inline-block; width: 100%;">
                            <input type="password" id="secret_key" name="secret_key" required
                                style="width: 100%; padding: 10px; padding-right: 40px; box-sizing: border-box;">
                            <button type="button" id="togglePassword" 
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                                        border: none; background: none; cursor: pointer; font-size: 18px; outline: none;">
                                🙈
                            </button>
                        </div>
                    <div class="button-container">
                        <button class="close-btn" type="button" onclick="closeCreateModalKey()">閉じる</button>
                        <button class="register-btn" type="button" id="keyCreate">更新</button>
                    </div>
                    </form>

                    </div>
                </div>
                <div class="log--container-wrapper">
                    <table class="log-container">
                        <thead>
                            <tr>
                                <th>号館</th>
                                <th>教室番号</th>
                                <th>個体識別番号</th>
                                <th>秘密鍵</th>
                                <th>号館教室編集</th>
                                <th>鍵編集</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody" class="log-table-body">
                            <?php if (!empty($classrooms)): ?>
                                <?php foreach ($classrooms as $classroom): ?>
                                    <tr>
                                        <td><?= $classroom['id'] ?></td>
                                        <td><?= $classroom['name'] ?></td>
                                        <td><?= $classroom['MODEL_NUMBER'] ?></td>
                                        <td><?= str_repeat('*', strlen($classroom['SECRET_KEY'])) ?></td>
                                        <td>
                                            <button class="btn btn-primary" onclick="openEditRoom(
                                                {
                                                    'classroomid':'<?= $classroom['classroomID'] ?>',
                                                    'id':'<?= $classroom['id'] ?>',
                                                    'name':'<?= $classroom['name'] ?>'
                                                }
                                            )">  ✏️ 編集</button>
                                            <!-- <button class="edit-btn" onclick="openModal('deleteKey')">削除</button> -->
                                                <button type="button" class="btn btn-danger" id="deleteRoom" 
                                                        data-classroomID="<?= $classroom['classroomID'] ?>" 
                                                        data-room-name="<?= htmlspecialchars($classroom['name'], ENT_QUOTES, 'UTF-8') ?>"
                                                        onclick="deleteRoom(this)">🗑️ 削除</button>

                                        </td>
                                        <td><button class="btn btn-update" onclick="openCreateKey(
                                                {
                                                    'classroomid':'<?= $classroom['classroomID'] ?>'
                                                    // ,'id':'< ?= $classroom['id'] ?>',
                                                    // 'name':'< ?= $classroom['name'] ?>'
                                                }
                                            )"> 🔑 更新</button>
                                            
                                            <button  class="btn btn-danger" type="button" id="DeleteKeyRelated"
                                                    keyRelatedID="<?= $classroom['classroomID'] ?>"
                                                    DelKeyClassName="<?= htmlspecialchars($classroom['name'], ENT_QUOTES, 'UTF-8') ?>"
                                                    ondblclick="DeleteKeyRelated(this)">鍵の削除</button>
                                        </td>
                                        
                                    </tr>
                                    
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">表示する教室情報がありません。</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
<?php } else { ?>
    ログインできませんでした・・・ 
    <?php if (!empty($err)) { echo "<p>エラー: $err</p>"; } ?>
    <?= $_SESSION['name'] ?>
    <a href="../login/login.php">ログイン画面へ</a>
<?php } ?>
</body>
</html>
