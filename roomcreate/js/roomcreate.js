const modal = document.getElementById('modal');
const openModalBtn = document.getElementById('openModal');
const closeModalBtn = document.getElementById('closeModal');
function filterTable() {
    // 入力された検索文字列を取得（小文字に変換）
    const query = document.getElementById("searchQuery").value.toLowerCase();

    // テーブルのすべての行を取得
    const rows = document.querySelectorAll("#logTableBody tr");

    // 検索結果がない場合のメッセージ行を削除
    const noResultsMessage = document.getElementById("noResultsMessage");
    if (noResultsMessage) {
        noResultsMessage.remove();
    }

    // 一致する行があるかどうかを追跡
    let isAnyRowVisible = false;

    // 各行をループ処理してフィルタリング
    rows.forEach(row => {
        // 検索対象の列（教室名）を取得
        const classroomName = row.children[1]?.textContent.toLowerCase(); // 教室名の列（2番目の列）

        // 検索条件に一致するかを判定
        if (classroomName && classroomName.includes(query)) {
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
        messageCell.colSpan = 5; // 列数に合わせて
        messageCell.textContent = "検索結果が見つかりませんでした";
        messageRow.appendChild(messageCell);
        document.getElementById("logTableBody").appendChild(messageRow);
    }
}




// 新規教室登録モーダルを開く
function openCreateModal() {
    const createModal = document.getElementById('create_user1');
    const overlay = document.getElementById('overlay');
    
    createModal.style.display = 'block';
    overlay.style.display = 'block';

    // アニメーションを遅延させて滑らかに表示
    setTimeout(() => {
        createModal.style.opacity = 1;
        overlay.style.opacity = 1;
    }, 10);
}


$(function(){
    $('#createRoom').on('click', function(e){
        e.preventDefault(); // フォームのデフォルト送信を防ぐ
        $.ajax({
            url: 'php/register_building.php', //送信先
            type: 'POST', //送信方法
            datatype: 'json', //受け取りデータの種類
            data: {
                new_building_id: $('#new_building_id').val(),
                new_building_name: $('#new_building_name').val() 
            }
        })
        .done(function(data) {
            console.log('通信成功');
            console.log(data);
            alert('教室登録が完了しました！');
            window.location.reload();    // ページをリロードして変更を反映
        })
        .fail(function(xhr, textStatus, errorThrown) {
            console.error('通信失敗');
            console.error('ステータス: ' + textStatus);
            console.error('エラー: ' + errorThrown);
            console.error('レスポンス: ' + xhr.responseText);
            alert('通信中にエラーが発生しました。詳細はコンソールを確認してください。');
        });
    });
}); 

document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("togglePassword");
    const passwordField = document.getElementById("secret_key");

    toggleButton.addEventListener("click", function () {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleButton.textContent = "👁️"; // 目を開けるアイコン
        } else {
            passwordField.type = "password";
            toggleButton.textContent = "🙈"; // 目を隠すサルアイコン
        }
    });
});


// 新規教室登録モーダルを閉じる
function closeCreateModal() {
    const createModal = document.getElementById('create_user1');
    const overlay = document.getElementById('overlay');
    
    createModal.style.opacity = 0;
    overlay.style.opacity = 0;

    setTimeout(() => {
        createModal.style.display = 'none';
        overlay.style.display = 'none';
    }, 300);
}
// 新規かぎ登録モーダルを閉じる
function closeCreateModalKey() {
    const createModal = document.getElementById('create_key');
    const overlay = document.getElementById('overlay');
    
    createModal.style.opacity = 0;
    overlay.style.opacity = 0;

    setTimeout(() => {
        createModal.style.display = 'none';
        overlay.style.display = 'none';
    }, 300);
}


function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('overlay');

    if (modal && overlay) {
        // フェードアウトアニメーション
        overlay.style.opacity = '0';
        modal.style.opacity = '0';

        // 非表示にする
        setTimeout(() => {
            overlay.style.display = 'none';
            modal.style.display = 'none';
        }, 300); // アニメーション終了後に非表示
    }
}


function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('overlay');

    if (modal && overlay) {
        modal.style.display = 'block';
        overlay.style.display = 'block';

        setTimeout(() => {
            modal.style.opacity = 1;
            overlay.style.opacity = 1;
        }, 10);
    } else {
        console.error('モーダルまたはオーバーレイが見つかりません。');
    }
}






// 情報編集フォームを開く関数
function openEditRoom(editUser) {
    var inEditRoom = document.querySelector('#edit-room');
    var inBuildingName = document.querySelector('#building_name');
    var inRoomNumber = document.querySelector('#room_number');

    // 値をセット
    inEditRoom.value = editUser['classroomid']; // 隠しフィールドにセット
    inBuildingName.value = editUser['id']; // 号館
    inRoomNumber.value = editUser['name']; // 教室番号

    console.log('classroomid set to: ', inEditRoom.value);
    console.log('openEditRoom called');
    console.log(editUser);

    // オーバーレイと編集フォームを表示
    var overlay = document.getElementById('overlay');
    overlay.style.display = 'block';
    overlay.style.opacity = '1';

    var form = document.querySelector('.edit_user');
    form.style.display = 'block';
    form.style.opacity = '1';
}


$(function() {
    $('#editRoom').on('click', function() {
        // Ajax通信
        // console.log("ajaxUrl: ", ajaxUrl); 
        console.log('classroomid to send: ', $('#edit-room').val());
        console.log('building_name to send: ', $('#building_name').val());
        console.log('room_number to send: ', $('#room_number').val());
        $.ajax({
            url: 'php/roomedit.php', // AjaxのURL
            type: 'POST',
            dataType: 'json',
            data: {
               classroomid: $('#edit-room').val(), // 編集される教室のID 
                id: $('#building_name').val(), // 号館の名前
                name: $('#room_number').val()  // 教室番号
            }
        })
        .done(function(data) {
            console.log('通信成功');
            console.log(data);
            alert('教室編集が完了しました！');
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



// $(function(){
//     $('#editRoom').on('click', function(){
//         $.ajax({
//         url: 'php/roomedit.php',
//         type: 'POST',
//         dataType: 'json',
//         data:{
//             classroomid: $('#edit-room').val(),
//             id: $('#building_name').val(),
//             name: $('#room_number').val(),
//     },
//     })
//         // Ajax通信が成功した時
//         .done(function(data) {
//           // 通信成功時の処理
//           console.log('通信成功');
//           console.log(data);  // 返ってきたデータを確認
  
//           // 結果を画面に表示
//           // $('#result').html("<p>ID番号 " + data[0].id + " は「" + data[0].name + "」さんです。<br>メールアドレスは「" + data[0].mail + "」です。</p>");
//       })
//       .fail(function(xhr, textStatus, errorThrown) {
//           // 通信失敗時の処理
//           console.error('通信失敗');
//           console.error('ステータス: ' + textStatus); // エラーステータス
//           console.error('エラーメッセージ: ' + errorThrown); // エラーメッセージ
//           console.error('レスポンス: ' + xhr.responseText); // サーバーからのレスポンス
  
//           // エラーメッセージを画面に表示
//           $('#result').html("<p>エラーが発生しました: " + errorThrown + "</p>");
//       });
//     });
//   });







// 閉じる関数（オーバーレイとモーダルを非表示にする）
function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    var overlay = document.getElementById('edit-overlay');

    modal.style.display = 'none';
    overlay.style.display = 'none';
}
// 削除フォームを開く関数
function openEditUser(deleteKey) {
    var deleteKey = document.getElementById('deleteKey');
    // var inBuildingName = document.querySelector('#building_name');
    // var inRoomNumber = document.querySelector('#room_number');
    var inSecretKey = document.querySelector('#secret_key');
    var inSerialNumber = document.querySelector('#serial_number');
    
    console.log(editUser);

    // フィールドに値をセット
    // inBuildingName.value = editUser['building_name'];
    // inRoomNumber.value = editUser['room_number'];
    inSecretKey.value = editUser['secret_key'];
    inSerialNumber.value = editUser['serial_number'];

    // オーバーレイを表示
    document.getElementById('edit-overlay').style.display = 'block';
    document.getElementById('edit-overlay').style.opacity = '1';
    
    // 編集フォームを表示
    deleteKey.style.display = 'block';
    deleteKey.style.opacity = '1';
}

// 更新ボタンまたは号室削除ボタンのクリックイベントハンドラ
// 更新ボタンまたは号室削除ボタンのクリックイベントハンドラ


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
