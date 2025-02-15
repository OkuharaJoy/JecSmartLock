<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);





// $_SESSION = array();

$user = $_POST['mail'] ?? "";  // フォーム送信のname="user"のデータを受け取り
$pass = isset($_POST['pass']) ? hash('sha512', $_POST['pass']) : ""; // フォーム送信のname="pass"のデータを受け取り
$id = 0;
$err = "";
$login_user = "";
$classrooms = [];
$status = $_SESSION['OPEN_CLOSE_STATUS'] ?? 0; // セッションからステータスを取得、なければデフォルト値0を使用

// ログイン処理とその他の処理...
// （この部分はそのまま残しておきます）
try {
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
            $login_user = $row['MAIL'];
            if (trim($login_user) == trim($user)) {
                $_SESSION['name'] = $user;
                $id = $row['USERID'];
            }
        }
    }

    $user_sql = 'SELECT u.USERID, u.NAME, u.MAIL, u.PASS, u.TEL, u.PERMISSION, 
    TO_CHAR(u.USE_PERIOD, \'YYYY-MM-DD HH24:MI:SS\') AS USE_PERIOD, 
    c.CLASSROOMNAME
    FROM users u
    LEFT JOIN CLASSROOM c ON (u.CLASSROOMID = c.CLASSROOMID)
    ORDER BY USERID';

    // WHERE u.USERID = :USERID;
    $users = array();
    $err = "";
    $i = 0;
    try {
        $dbh = new PDO($dsn, $dbuser, $dbpass);
        $err .= 'データベースに接続しました。';
        $stmt = $dbh->query($user_sql);

        while ($row = $stmt->fetch()) {
            $user = array();
            $user['userid'] = $row['USERID'];
            $user['name'] = $row['NAME'];
            $user['mail'] = $row['MAIL'];
            $user['pass'] = $row['PASS'];
            $user['tel'] = $row['TEL'];
            $user['permission'] = $row['PERMISSION'];
            $user['use_period'] = $row['USE_PERIOD'];
            $user['userclassroom'] = $row['CLASSROOMNAME'];
            $users[$i++] = $user;
        }

        $classroom_sql = 'SELECT CLASSROOMID, CLASSROOMNAME from CLASSROOM';
        $stmt = $dbh->query($classroom_sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $CLASSROOM = [];
            $CLASSROOM['classroomID'] = $row['CLASSROOMID'];
            $CLASSROOM['name'] = $row['CLASSROOMNAME'];
            $classrooms[] = $CLASSROOM;
        }
    } catch (PDOException $e) {
        $err .= '接続に失敗しました: ' . $e->getMessage();
    }

    // 接続終了
    $stmt = null;
    $dbh = null; // PDOオブジェクトにnullを代入してPDOとの接続を解除

} catch (PDOException $e) {
    $err .= "catch(" . $e->getMessage() . ")";
}

//  print_r($users); 消さないデー
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <title>userControl</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/userControl.js"></script>
    <script src="js/script.js"></script>
    <script>
        function deleteUser(button) {
            var userid = button.getAttribute("data-userid");
            console.log("取得したuserid:", userid);

            // 確認ダイアログを表示
            var confirmation = confirm("本当にユーザーを削除しますか？");

            if (confirmation) {
                // ユーザーが「OK」を選択した場合、Ajaxリクエストを送信
                $.ajax({
                    type: "POST",
                    url: "php/userDelete.php",
                    data: { userid: userid },
                    success: function (response) {
                        alert("ユーザー削除が成功しました！");
                        // 必要な場合、画面を更新する処理を追加
                        window.location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log("xhr:", xhr);
                        console.log("status:", status);
                        console.log("error:", error);
                        alert("ユーザー削除に失敗しました: " + error);
                    }
                });
            } else {
                console.log("ユーザー削除がキャンセルされました");
            }
        }
    </script>

    <!-- Ajax実装出来たら リロードせずに新規情報を表示 -->
    <!-- <script>
        $(function() {
            $.ajax({
                url:'php/selectUser.php',
                type:'POST',
                dataType: 'json',
                data:{
                    'id': $('#id_number').val()
                }
            })
            .done( function(data) {
                $('#result').html("<p>ID番号"+data[0].id+"は「"+data[0].name+"」さんです。<br>メールアドレスは「"+data[0].mail+"」です。</p>");
                console.log('通信成功');
                console.log(data);
            })
            // Ajax通信が失敗した時
            .fail( function(data) {
                $('#result').html(data);
                console.log('通信失敗');
                console.log(data);
            });
        });
    </script>  -->
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
                    <h2>ユーザー管理</h2>
                    <!-- ユーザー検索ボックス -->
                    <div class="tag">
                        <input type="text" id="searchQuery" class="search-box" placeholder="検索欄(例:電子 090------)"
                            onkeyup="filterTable()">
                        <!-- ユーザー登録 -->
                        <div id="overlay" onclick="closeCreateUser()"></div>
                        <p class="open-create_user" onclick="openCreateUser('create_user1')">
                            <span>ユーザー作成</span>
                        </p>

                        <!-- ユーザー作成フォーム -->
                        <div class="create_user" id="create_user1" style="display:none;">
                            <div class="model_title" id="model_lavel">
                                <form>
                                    <h3>ユーザー作成</h3>
                                    <label for="role">管理権限:</label>
                                    <select id="role" name="role" required onchange="updateFormFields()">
                                        <option value="">権限を選択してください</option>
                                        <hr>
                                        <?php if ($_SESSION['permission'] == 1) { ?>  <!-- 管理者は全て選択可能 -->
                                            <option value="1">管理者</option>
                                            <option value="2">教員</option>
                                        <?php } ?>
                                        <option value="3">学生</option>  <!-- 学生は常に選択可能 -->
                                    </select>

                                    <!-- その他のフォームフィールド -->
                                    <label for="name">名前:</label>
                                    <input type="text" id="name" name="name" placeholder="例:JZ電子　太郎" required>
                                    <label for="email">メールアドレス:</label>
                                    <input type="email" id="mail" name="email" required>
                                    <label for="password">パスワード:</label>
                                    <input type="password" id="password" name="password" required>
                                    <label for="password-confirm">パスワード（確認用）:</label>
                                    <input type="password" id="password-confirm" name="password-confirm" required>
                                    <label for="edit-phone1">携帯番号:</label>
                                    <input type="tel" id="edit-phone1" name="edit-phone1" required>
                                    <!-- 開けられる号室 (学生用) -->
                                    <div id="student-fields" style="display: none;">
                                        <label for="open-room-number">施錠号室を指定:</label>
                                        <select id="open-room-number" name="open-room-number">
                                            <?php foreach ($classrooms as $classroom) { ?>
                                                <option value="<?= $classroom['classroomID'] ?>"><?= $classroom['name'] ?></option>
                                            <?php } ?>
                                        </select>

                                        <label for="use-period">利用期限:</label>
                                        <input type="datetime-local" id="use-period" name="use_period">
                                    </div>  
                                    <div class="button-container">
                                        <button  class="close-btn" type="button" onclick="closeCreateUser()">閉じる</button>
                                        <button class="register-btn" type="button" id="registerButton">作成</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--各ユーザーの編集ボタンを押したらユーザーの持っている情報(管理者権限、教室番号)などを表示させたい-->
                    <!-- ユーザー情報編集 -->
                    <div id="edit-overlay" onclick="closeEditUser()" style="display: none; opacity: 0;"></div>
                    <div class="edit_user" id="edit_user1" style="display: none; opacity: 0;">
                    <form id="editUserForm">
                        <h3>情報編集</h3>
                        <input type="hidden" id="edit-user">

                        <label for="edit-role">管理権限:</label>
                        <select id="edit-role" name="edit-role" required onchange="toggleFields()">
                            <option value="1" id="role-admin">管理者</option>
                            <option value="2" id="role-teacher">教員</option>
                            <option value="3" id="role-student">学生</option>
                        </select>
                        
                        <!-- 以降のフォーム項目はそのまま -->
                        <label for="edit-name">名前:</label>
                        <input type="text" id="edit-name" name="edit-name" required>

                        <label for="edit-email">メールアドレス:</label>
                        <input type="email" id="edit-email" name="edit-email" required>

                        <label for="edit-password">新しいパスワード（更新時のみ入力）</label>
                        <input type="password" id="edit-password" name="edit-password" placeholder="未入力で保持">

                        <label for="edit-password-confirm">新しいパスワード（再入力）:</label>
                        <input type="password" id="edit-password-confirm" name="edit-password-confirm" placeholder="確認入力（未入力で保持）">

                        <label for="edit-phone">携帯番号:</label>
                        <input type="tel" id="edit-phone" name="edit-phone" required>

                        <!-- 学生専用フィールド -->
                        <div id="student-fields1" style="display: none;">
                            <label for="edit-userclassroom">施錠号室を指定:</label>
                            <select id="edit-userclassroom" name="edit-userclassroom">
                                <?php foreach ($classrooms as $classroom): 
                                    // classroomIDとuserclassroomを適切に型変換し、比較
                                    $classroomID = strval($classroom['classroomID']);
                                    $userClassroom = strval($user['userclassroom']); // ユーザーの号室も文字列に変換

                                    $selected = ($classroomID === $userClassroom) ? "selected" : ""; 
                                ?>
                                    <option value="<?= htmlspecialchars($classroomID, ENT_QUOTES, 'UTF-8') ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($classroom['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                                <label for="edit-use-period">利用期限:</label>
                                    <input type="datetime-local" id="edit-use-period" name="use_period" value="<?= date('Y-m-d\TH:i', strtotime($user['use_period'])) ?>">
                        </div>
                        

                        <div class="button-container1">
                            <button class="close-btn" type="button" onclick="closeEditUser()">閉じる</button>
                            <button class="update-btn" type="button" id="updateButton">更新</button>
                        </div>
                    </form>

                            <script>
           function toggleFields() {
    var roleSelect = document.getElementById('edit-role');
    var selectedRole = roleSelect.value;

    // ※ここでは、管理者や教員がアカウントを編集する際、学生用フィールドは不要なので非表示にする
    document.getElementById('student-fields1').style.display = 'none';

    // ログインユーザーが学生の場合は、権限選択を固定して変更不可にする
    if (userPermission == 3) {
        roleSelect.disabled = true;
        roleSelect.value = "3";
    }
    // ログインユーザーが教員または管理者の場合
    else if (userPermission == 1 || userPermission == 2) {
        // 他のユーザーの編集の場合、学生用の選択肢を無効化して非表示
        var studentOption = document.getElementById('role-student');
        if(studentOption) {
            studentOption.disabled = true;
            studentOption.style.display = 'none';
        }

        // 自分のアカウントを編集する場合は、権限変更を可能にする（自分のアカウントかどうかを確認）
        var editUserId = document.getElementById('edit-user').value; // 編集対象のユーザーIDを取得
        var loggedInUserId = <?php echo json_encode($user['id']); ?>; // ログインユーザーのID（PHPから出力）

        if (editUserId == loggedInUserId) {
            roleSelect.disabled = false; // 自分のアカウントなら権限を変更可能にする
        }
    }
}

                            </script>
                    </div>
                    <div class="user--container-wrapper">
                        <!-- ユーザー管理部分 -->
                        <table class="user-container">
                            <thead>
                                <tr>
                                    <th>ユーザー</th>
                                    <th>メールアドレス</th>
                                    <th>携帯番号</th>
                                    <th>権限</th>
                                    <th>利用教室</th>
                                    <th>利用期限</th>
                                    <th>編集</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <?php foreach ($users as $user) { ?>
                                    <tr>
                                        <td><?= $user['name'] ?> </td>
                                        <td><?= $user['mail'] ?> </td>
                                        <td><?= !empty($user['tel']) ? $user['tel'] : 'なし' ?> </td>
                                        <?php if ($user['permission'] == 1) { ?>
                                            <td>管理者</td>
                                            <td><?= !empty($user['userclassroom']) ? $user['userclassroom'] : 'なし' ?></td>
                                            <td>なし</td>
                                        <?php } elseif ($user['permission'] == 2) { ?>
                                            <td>教員</td>
                                            <td><?= !empty($user['userclassroom']) ? $user['userclassroom'] : 'なし' ?></td>
                                            <td>なし</td>
                                        <?php } elseif ($user['permission'] == 3) { ?>
                                            <td>学生</td>
                                            <td><?= !empty($user['userclassroom']) ? $user['userclassroom'] : 'なし' ?></td>
                                            <td><?= !empty($user['use_period']) ? date('Y-m-d\TH:i', strtotime(str_replace('/', '-', $user['use_period']))) : '' ?></td>
                                        <?php } ?>
                                        <!-- 編集ボタンを表示する条件 -->
                                        <td>
                                            <?php if ($_SESSION['permission'] == 1 || ($_SESSION['permission'] == 2 && $user['permission'] == 3)) { ?> 
                                                <button onclick="openEditUser(
                                                    {
                                                        'userid':'<?= $user['userid'] ?>',
                                                        'name':'<?= $user['name'] ?>',
                                                        'mail':'<?= $user['mail'] ?>',
                                                        'pass':'<?= $user['pass'] ?>',
                                                        'tel':'<?= $user['tel'] ?>',
                                                        'permission':'<?= $user['permission'] ?>',
                                                        'userclassroom': '<?= htmlspecialchars($user['userclassroom'], ENT_QUOTES, 'UTF-8') ?>',
                                                        'use_period': '<?= !empty($user['use_period']) ? date('Y-m-d\TH:i', strtotime($user['use_period'])) : '' ?>'
                                                    }
                                                )" class="btn btn-primary">
                                                    ✏️ 編集
                                                </button>
                                            <?php } else { ?>
                                                <span class="text-muted">編集不可</span>  <!-- 編集不可の表示 -->
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php if ($_SESSION['permission'] == 1 || ($_SESSION['permission'] == 2 && $user['permission'] == 3)) { ?>  
                                                <button type="button" class="btn btn-danger" data-userid="<?= $user['userid'] ?>" onclick="deleteUser(this)">
                                                    🗑️ 削除
                                                </button>
                                            <?php } else { ?>
                                                <span class="text-muted">削除不可</span>  <!-- 削除不可の表示 -->
                                            <?php } ?>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>
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

</html>
