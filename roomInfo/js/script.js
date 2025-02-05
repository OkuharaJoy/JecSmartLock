$(document).ready(function() {
    $('#confirmButton').prop('disabled', false);

    // 「扉の開閉」ボタンをクリックしたらオーバーレイを表示
    $('#toggleDoor').click(function() {
        $('#overlay').fadeIn();  // オーバーレイをフェードインで表示
    });

    // オーバーレイの「閉じる」ボタンをクリックしたらオーバーレイを閉じる
    $('#closeOverlay').click(function() {
        $('#overlay').fadeOut();  // オーバーレイをフェードアウトで非表示
    });


     // フォームを送信する関数
      function submitForm() {
        document.getElementById('dateForm').submit();
    }
});


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


