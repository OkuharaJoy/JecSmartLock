function openCreateKey(keyCreate) {
    const createModal = document.getElementById('create_key');
    const overlay = document.getElementById('overlay');
    
    createModal.style.display = 'block';
    overlay.style.display = 'block';

    // アニメーションを遅延させて滑らかに表示
    setTimeout(() => {
        createModal.style.opacity = 1;
        overlay.style.opacity = 1;
    }, 10);

    // 値をセット
    var inclassroomid = document.querySelector('#inclassroomid');
    inclassroomid.value = keyCreate['classroomid']; // 隠しフィールドにセット
    console.log('classroomid : ', inclassroomid.value);
}
$(function() {
    $('#keyCreate').on('click', function() {
        // Ajaxリクエストを送信
        $.ajax({
            url: "php/keyCreate.php",
            type: "POST",
            dataType: 'json',
            data: {
                classroomID: $('#inclassroomid').val(),
                model_number: $('#model_number').val(),
                secret_key: $('#secret_key').val() 
            },
            success: function(response) {
                    if (response.success) {
                        alert(response.message); // 成功メッセージを表示
                        window.location.reload();    // ページをリロードして変更を反映
                    } else {
                        alert("エラー: " + response.message); // エラー内容を表示
                    }
            },
            error: function(xhr) {
                console.error("リクエストエラー: ", xhr.responseText); // エラー内容をコンソールに表示
                // alert("リクエストエラー: " + xhr.responseText); // Ajaxリクエスト自体のエラー時
            }
        });
    });
});
