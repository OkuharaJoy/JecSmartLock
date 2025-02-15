  
    // モーダルとアイコンの要素を取得
    const modal = document.getElementById('modal');
    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    function filterTable() {
     // 入力された検索文字列を取得（小文字に変換）
      const query = document.getElementById("searchQuery").value.toLowerCase();

      // テーブルのすべての行を取得
      const rows = document.querySelectorAll("#userTableBody tr");

      // 検索結果がない場合のメッセージ行を削除
      const noResultsMessage = document.getElementById("noResultsMessage");
      if (noResultsMessage) {
          noResultsMessage.remove();
      }

      // 一致する行があるかどうかを追跡
      let isAnyRowVisible = false;

      // 各行をループ処理してフィルタリング
      rows.forEach(row => {
          // 検索対象の列（名前、メールアドレス、携帯番号、管理権限）を取得
          const name = row.children[0]?.textContent.toLowerCase();
          const email = row.children[1]?.textContent.toLowerCase();
          const tel = row.children[2]?.textContent.toLowerCase();
          const permission = row.children[3]?.textContent.toLowerCase();

          // 検索条件に一致するかを判定
          if (
              (name && name.includes(query)) ||
              (email && email.includes(query)) ||
              (tel && tel.includes(query)) ||
              (permission && permission.includes(query))
          ) {
              row.style.display = ""; // 一致する場合は表示
              isAnyRowVisible = true; // 一致する行があった場合はフラグを立てる
          } else {
              row.style.display = "none"; // 一致しない場合は非表示
          }
      });

        // 検索結果がない場合、メッセージを表示
        if (!isAnyRowVisible) {
            const messageRow = document.createElement("tr");
            messageRow.id = "noResultsMessage";
            const messageCell = document.createElement("td");
            messageCell.colSpan = 8; // 列数に合わせて
            messageCell.textContent = "検索結果が見つかりませんでした";
            messageRow.appendChild(messageCell);
            document.getElementById("userTableBody").appendChild(messageRow);
        }
    }
    
    
      // ページ読み込み時にフォームが管理者デフォルトになるように処理
      window.onload = function() {
        toggleFields();
      };
    
      // 閉じるボタンがクリックされたときの処理（フォームを閉じる処理）
      function closeCreateUser() {
        // フォーム閉じる処理をここに書く
      }
    

 
    // フォームの初期設定
    function setUserForm(role) {
      // 初期表示状態を設定
      var roleSelect = document.getElementById('edit-role');
      var departmentField = document.getElementById('teacher-fields');
      var adminField = document.getElementById('admin-fields');
      var studentField = document.getElementById('student-fields');
    
      // 権限に応じたフィールド表示
      roleSelect.value = role; // ユーザーの役割を選択肢として設定
      toggleFields(); // 役割に応じてフォーム項目を更新する
    
      // 各役割に応じたフォーム項目
      if (role === 'user') {
          // 教員の場合
          departmentField.style.display = 'block';
          adminField.style.display = 'none';
          studentField.style.display = 'none';
      } else if (role === 'admin') {
          // 管理者の場合
          departmentField.style.display = 'none';
          adminField.style.display = 'block';
          studentField.style.display = 'none';
      } else if (role === 'student') {
          // 学生の場合
          departmentField.style.display = 'none';
          adminField.style.display = 'none';
          studentField.style.display = 'block';
      }
    }
    




    
//     // ユーザー権限に応じて入力フィールドを制御する関数
//     function toggleFields() {
//       var role = document.getElementById('edit-role').value;
//       // 学生専用フィールド
//     var studentFields = document.getElementById('student-fields');
//     if (studentFields) {
//         studentFields.style.display = (role == '3') ? 'block' : 'none';
//     }

//     // 管理者、教員、学生それぞれのフィールドを切り替え
//     var adminFields = document.getElementById('admin-fields');
//     var teacherFields = document.getElementById('teacher-fields');
//     if (adminFields && teacherFields) {
//         adminFields.style.display = (role == '1') ? 'block' : 'none';
//         teacherFields.style.display = (role == '2') ? 'block' : 'none';
//     }

//     // 学生の場合の特別な処理
//     if (role == '3') {
//         document.getElementById('student-fields').style.display = 'block';
//     } else {
//         document.getElementById('student-fields').style.display = 'none';
//     }
// }
    
    
    
function openCreateUser(create_userId) {
    const overlay = document.getElementById('overlay');
    const create_user = document.getElementById(create_userId);

    overlay.style.display = 'block';
    create_user.style.display = 'block';

    setTimeout(() => {
        overlay.style.opacity = 1;
        create_user.style.opacity = 1;
    }, 10);
}

function closeCreateUser() {
    const overlay = document.getElementById('overlay');
    const create_user = document.getElementById('create_user1');

    overlay.style.opacity = 0;
    create_user.style.opacity = 0;

    setTimeout(() => {
        create_user.style.display = 'none';
        overlay.style.display = 'none';
    }, 500);
}








function openEditUser(editUser) {
  console.log('🔹 openEditUser() 呼び出し:', editUser);

  // 各フィールドを取得
  const inEdit_user = document.querySelector('#edit-user');
  const inPermission = document.querySelector('#edit-role');
  const inName = document.querySelector('#edit-name');
  const inMail = document.querySelector('#edit-email');
  const inPassword = document.querySelector('#edit-password');
  const inPasswordConfirm = document.querySelector('#edit-password-confirm');
  const inTel = document.querySelector('#edit-phone');
  const inClassroom = document.querySelector('#edit-userclassroom'); // セレクトボックス
  const inUsePeriod = document.querySelector('#edit-use-period');
  const studentFields = document.getElementById('student-fields1'); // 学生専用のフィールド

  // 🔹 ユーザー情報をフォームに設定
  inEdit_user.value = editUser['userid'];
  inName.value = editUser['name'];
  inMail.value = editUser['mail'];
  inPermission.value = String(editUser['permission']);
  if (editUser['userclassroom'] && inClassroom) {
    const userClassroomName = editUser['userclassroom']; // 施錠号室の名前
    console.log('📌 取得した施錠号室 (userclassroom):', userClassroomName);

    // classroomID をマッピング
    let matchedClassroomID = null;
    Array.from(inClassroom.options).forEach(option => {
        if (option.textContent.trim() === userClassroomName.trim()) {
            matchedClassroomID = option.value;
        }
    });

    if (matchedClassroomID) {
        console.log('✅ 対応する classroomID:', matchedClassroomID);
        setTimeout(() => {
            inClassroom.value = matchedClassroomID;
            console.log('✅ 設定後の inClassroom.value:', inClassroom.value);
        }, 100);
    } else {
        console.warn('⚠️ userclassroom に対応する classroomID が見つかりません:', userClassroomName);
    }
}

  // 🔹 use_period（利用期限）の設定
  if (editUser['use_period']) {
    let formattedDate = editUser['use_period'].trim().replace(' ', 'T').slice(0, 16);
    inUsePeriod.value = formattedDate;
    console.log('📌 設定した利用期限:', formattedDate);
  } else {
    inUsePeriod.value = '';
    console.log('📌 利用期限なし');
  }

  // 🔹 学生専用フィールドの表示制御
  if (editUser['permission'] == "3") { // 学生
    studentFields.style.display = 'block';
    inTel.style.display = 'none'; // 電話番号を非表示
    inPermission.disabled = true; // 権限変更不可
    document.querySelector('label[for="edit-phone"]').style.display = 'none';
    inPassword.disabled = false;
    inPasswordConfirm.disabled = false;
  } else {
    studentFields.style.display = 'none';
    inTel.style.display = 'block'; // 電話番号を表示
    inTel.value = editUser['tel'] || '';
    inPermission.disabled = false;
    inPassword.disabled = false;
    inPasswordConfirm.disabled = false;
  }

  // 🔹 編集画面を表示
  document.getElementById('edit-overlay').style.display = 'block';
  document.getElementById('edit-overlay').style.opacity = '1';
  const edit_user1 = document.getElementById('edit_user1');
  edit_user1.style.display = 'block';
  edit_user1.style.opacity = '1';
  console.log('📌 編集画面表示完了');
}


// 更新ボタンのクリック時にSHA-512を使用する
$(function() {
  $('#updateButton').on('click', async function() {
    const password = $('#edit-password').val();
    const passwordConfirm = $('#edit-password-confirm').val();

    if (password && password !== passwordConfirm) {
      alert('パスワードが一致しません。再度入力してください。');
      return; // 一致しない場合は更新処理を中断
    }

    // SHA-512 ハッシュ化
    let passwordToSend = '';
    if (password) {
      const hashBuffer = await crypto.subtle.digest('SHA-512', new TextEncoder().encode(password));
      passwordToSend = Array.from(new Uint8Array(hashBuffer))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('');
    }

    // Ajax送信
    $.ajax({
      url: 'php/useredit.php',
      type: 'POST',
      dataType: 'json',
      data: {
        userId: $('#edit-user').val(),
        name: $('#edit-name').val(),
        mail: $('#edit-email').val(),
        password: passwordToSend,  // ハッシュ化したパスワード
        tel: $('#edit-phone').val(),
        role: $('#edit-role').val(),
        userclassroom: $('#edit-userclassroom').val(),
        use_period: $('#edit-use-period').val()  // 新しい日付
      },
    })
    .done(function(data) {
      console.log('通信成功');
      console.log(data);
      alert('更新が完了しました');
      window.location.reload();    // ページをリロードして変更を反映
    })
    .fail(function(xhr, textStatus, errorThrown) {
      console.error('通信失敗');
      console.error('ステータス: ' + textStatus);
      console.error('エラーメッセージ: ' + errorThrown);
      console.error('レスポンス: ' + xhr.responseText);
      $('#result').html("<p>エラーが発生しました: " + errorThrown + "</p>");
    });
  });
});

   // 登録ボタンをクリックしたときに分数を除外する処理
   function validateDate() {
    var usePeriodInput = document.getElementById('use-period');
    var usePeriodValue = usePeriodInput.value;

    if (usePeriodValue) {
        // "YYYY-MM-DDTHH:MM" の形式になっているため、分部分を切り捨て
        var dateParts = usePeriodValue.split(':');
        // 分を 00 に設定
        dateParts[1] = "00";
        usePeriodInput.value = dateParts.join(':');
    }

    // フォームを送信
    alert('登録が完了しました。');
}





          function togglePasswordFields() {
            var checkbox = document.getElementById('change-password-checkbox');
            var passwordField = document.getElementById('edit-password');
            var confirmPasswordField = document.getElementById('edit-password-confirm');
        
    
            // 常に入力可能だが、値は設定されない
            passwordField.placeholder = '新しいパスワードを入力';
            confirmPasswordField.placeholder = '確認用パスワードを入力';
        }
        

    
    
          function closeEditUser() {
            // オーバーレイを非表示
        document.getElementById('edit-overlay').style.display = 'none';
        document.getElementById('edit-overlay').style.opacity = '0';
        
        // 編集フォームを非表示
        var editUser = document.getElementById('edit_user1');
        editUser.style.display = 'none';
        editUser.style.opacity = '0';
        }
        
        
    
     // サイドバー用
     $(document).ready(function() {
      var isMouseInSidebar = false; // マウスがサイドバー上にあるかどうかを示すフラグ
      var openThresholdPercentage = 1; // 左端からの何%以内でサイドバーを開くか設定（例：12%）
    
      // サイドバーの幅を取得し、コンテンツの幅を調整する関数
      function adjustContentWidth() {
          var sidebarWidth = $('.sidebar').hasClass('open') ? $('.sidebar').outerWidth() : 0;
          $('.content').css({
              'margin-left': sidebarWidth,
              'width': `calc(100% - ${sidebarWidth}px)` // コンテンツの幅を調整
          });
      }
    
      // 初期状態で幅を調整
      adjustContentWidth();
    
      // サイドバー上にマウスが入ったときは閉じない
      $('.sidebar').on('mouseenter', function() {
          isMouseInSidebar = true; // フラグを true に設定
      });
    
      // サイドバーからマウスが出たときに閉じることを許可
      $('.sidebar').on('mouseleave', function() {
          isMouseInSidebar = false; // フラグを false に設定
      });
    
    
      // 画面の左端から一定の割合にマウスがある場合にサイドバーを開く
      function getEdgePx() {
        return $(window).width() * (openThresholdPercentage / 100);
    }
    
    
      // ウィンドウサイズが変更されたとき、再設定
      $(window).resize(function() {
          adjustContentWidth(); // ウィンドウサイズ変更時にも幅を再調整
      });
    
      // PC画面でのみサイドバーの動作を有効にする
      $(document).mousemove(function(e) {
          if (isPC()) { // PC画面の場合のみ処理を実行
              var edgePx = getEdgePx(); // 現在のウィンドウ幅に基づいて edgePx を取得
    
              if (e.pageX < edgePx) { // 左端edgePx%以内にマウスがあるとき
                  $('.sidebar').addClass('open');
                  adjustContentWidth(); // サイドバーを表示する場合はコンテンツの幅を再調整
              } else if (!isMouseInSidebar) { // マウスがサイドバー上にない場合のみ閉じる
                  $('.sidebar').removeClass('open');
                  adjustContentWidth(); // サイドバーを非表示にする場合も幅を再調整
              }
          }
      });
    
      // スマホかPCかを判定する関数
      function isPC() {
          return window.innerWidth >= 481; // PC画面のサイズ基準を481px以上に設定
      }
    });
    
            
    $(document).ready(function() {
      // スマホ画面の判定
      function isMobile() {
        return $(window).width() <= 480; // 480px以下をスマホとみなす
      }
    
      var isSidebarOpen = false; // サイドバーの状態を管理
    
      // メニューアイコンがクリックされた時の処理
      $('#menuIcon').on('click', function(event) {
        console.log('Menu icon clicked'); // コンソールにメッセージを出力
        event.stopPropagation(); // イベントのバブリングを防ぐ
        if (isMobile()) { // スマホ画面の場合のみ処理を実行
          isSidebarOpen = !isSidebarOpen; // サイドバーの状態をトグル
          $('.sidebar').toggleClass('open', isSidebarOpen); // サイドバーを開閉
          adjustContentWidth(); // サイドバー開閉時にコンテンツ幅を調整
        }
      });
    
      // オーバーレイがクリックされた時の処理（サイドバーを閉じる）
      $('#overlay').on('click', function() {
        isSidebarOpen = false;
        $('.sidebar').removeClass('open');
        $('#overlay').hide();
        adjustContentWidth();
      });
    
    
      // ウィンドウサイズ変更時に幅を再調整
      $(window).resize(function() {
        adjustContentWidth();
      });
    
      // 初期状態でコンテンツの幅を調整
      adjustContentWidth();
    });
    
    
       // ボタンがクリックされた時のイベントを監視
    //    $('#updateButton').on('click', function() {
    //     console.log("更新ボタンがクリックされました");
    // });