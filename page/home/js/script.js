document.addEventListener("DOMContentLoaded", function () {
  // 全ての号室を表示・非表示トグル
  const toggleCheckbox = document.getElementById("toggleCheckbox");
  toggleCheckbox.addEventListener("change", function () {
    const roomsContainers = document.querySelectorAll(".rooms");
    roomsContainers.forEach((container) => {
      container.style.display = toggleCheckbox.checked ? "grid" : "none";
    });
  });
});
//部屋のコンテナを開閉するためのスクリプト
function toggleRooms1() {
  const roomsContainer = document.getElementById('rooms-container1');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms2() {
  const roomsContainer = document.getElementById('rooms-container2');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms3() {
  const roomsContainer = document.getElementById('rooms-container3');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms4() {
  const roomsContainer = document.getElementById('rooms-container4');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms5() {
  const roomsContainer = document.getElementById('rooms-container5');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms6() {
  const roomsContainer = document.getElementById('rooms-container6');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms7() {
  const roomsContainer = document.getElementById('rooms-container7');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms8() {
  const roomsContainer = document.getElementById('rooms-container8');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms9() {
  const roomsContainer = document.getElementById('rooms-container9');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms10() {
  const roomsContainer = document.getElementById('rooms-container10');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}
function toggleRooms11() {
  const roomsContainer = document.getElementById('rooms-container11');
  if (roomsContainer.style.display === "none" || roomsContainer.style.display === "") {
    roomsContainer.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer.style.display = "none"; // 部屋を非表示
  }
}

function toggleRooms12() {
  const roomsContainer12 = document.getElementById('rooms-container12');
  if (roomsContainer12.style.display === "none" || roomsContainer12.style.display === "") {
    roomsContainer12.style.display = "grid"; // 部屋を表示
  } else {
    roomsContainer12.style.display = "none"; // 部屋を非表示
  }
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