<?php
session_start();
$_SESSION = array();


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sample.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap">
    <title>LoginPage</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <header>
    <h1>JEC SMART LOCK</h1>
</header>
  <div class="bg">
    <div class="container">
      <div id="login" class="tabcontent">
        <form action="../home/home.php" method="post">
          <h2>ログイン</h2>
          <label for="email">メールアドレス</label>
          <input type="text" id="mail" name="mail" required>
          <label for="password">パスワード</label>
          <input type="password" id="pass" name="pass" required>
          <a href="#">パスワードをお忘れの方</a>
          <button type="submit">ログイン</button>
        </form>
      </div>
      <div id="create" class="tabcontent" style="display:none;">
        <!-- Add account creation form here if needed -->
      </div>
    </div>
  </div>
  <script>
    function openTab(evt, tabName) {
      var i, tabcontent, tablinks;

      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    }


    // Set default tab to login
    document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("login").style.display = "block";
    });


      // ページ読み込み後に削除処理を非同期で呼び出す
      $(document).ready(function() {
        $.ajax({
            url: "userstudentDelete.php",  // 削除処理のファイル名
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log("削除完了: " + response.deleted + "件");
                if (response.deleted > 0) {
                    alert(response.deleted + "件 期限切れの学生アカウントが自動削除されました。");
                }
            },
            error: function(xhr, status, error) {
                console.error("削除エラー: " + error);
            }
        });
    });
  </script>
</body>
</html>
