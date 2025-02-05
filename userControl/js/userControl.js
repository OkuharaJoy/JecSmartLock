// 新規ユーザー作成
function updateFormFields() {
    var role = $('#role').val();
    console.log("選択された役割: " + role); // 役割の確認

    // 学生の場合
    if (role == "3") {
        $('#student-fields').show();
        $('#admin-fields, #teacher-fields').hide();
        $('#edit-phone1').prop('required', false); // 学生は携帯番号非必須
        $('#edit-phone1').hide(); // 学生の場合、携帯番号フィールドを非表示
        $('label[for="edit-phone1"]').hide(); // 学生の場合、携帯番号のラベルも非表示
        console.log("学生用フィールドを表示、携帯番号は非表示");
    } 
    // 管理者の場合
    else if (role == "1") {
        $('#student-fields, #teacher-fields').hide();
        $('#admin-fields').show();
        $('#edit-phone1').prop('required', true); // 管理者は携帯番号必須
        $('#edit-phone1').show(); // 管理者の場合、携帯番号フィールドを表示
        $('label[for="edit-phone1"]').show(); // 管理者の場合、携帯番号のラベルを表示
        console.log("管理者用フィールドを表示、携帯番号は必須");
    } 
    // 教員の場合
    else if (role == "2") {
        $('#student-fields, #admin-fields').hide();
        $('#teacher-fields').show();
        $('#edit-phone1').prop('required', true); // 教員は携帯番号必須
        $('#edit-phone1').show(); // 教員の場合、携帯番号フィールドを表示
        $('label[for="edit-phone1"]').show(); // 教員の場合、携帯番号のラベルを表示
        console.log("教員用フィールドを表示、携帯番号は必須");
    } 
    else {
        $('#student-fields, #admin-fields, #teacher-fields').hide();
        $('#edit-phone1').prop('required', false); // 役割が選択されていない場合は携帯番号非必須
        $('#edit-phone1').hide(); // 携帯番号フィールドを非表示
        $('label[for="edit-phone1"]').hide(); // 携帯番号のラベルも非表示
        console.log("役割が選択されていない、全フィールド非表示、携帯番号は非必須");
    }

    // 現在の携帯番号フィールドの状態を確認
    console.log("携帯番号フィールドのrequired属性: " + $('#edit-phone1').prop('required'));
}

$(function() {
    $('#register').on('click', function() {
        var role = $('#role').val();
        
        // 管理者または教員の場合、携帯番号の値を確認
        if (role == '1' || role == '2') {
            var telValue = $('#edit-phone1').val();
            console.log("携帯番号の値: " + telValue); // 取得した値をログに出力
        
            // 携帯番号が空でないか確認
            if (telValue.trim() === "") {
                alert("携帯番号は必須です");
                return;
            }
        }
        
        // 学生の場合、利用教室と利用期限の値を確認
        if (role == '3') {
            var openRoomNumber = $('#open-room-number').val();
            var usePeriod = $('#use-period').val();
            
            // 学生の場合、利用教室と利用期限が未入力の場合のチェック
            if (!openRoomNumber || !usePeriod) {
                alert("施錠号室と利用期限は必須です");
                return;
            }

            // 施錠号室と利用期限の値をコンソールで確認
            console.log("施錠号室: " + openRoomNumber);
            console.log("利用期限: " + usePeriod);
        }
        
        // フォーム送信
        $.ajax({
            url: 'php/userCreate.php', // 送信先のPHPファイル
            type: 'POST', // 送信方法
            datatype: 'json', // 受け取りデータの種類
            data: {
                userId: $('#edit-user1').val(),
                name: $('#name').val(), // ユーザー名
                mail: $('#mail').val(), // メールアドレス
                password: $('#password').val(), // パスワード
                tel: $('#edit-phone1').val(), // 携帯番号
                role: $('#role').val(), // 役割
                'open-room-number': $('#open-room-number').val(), // 学生の施錠号室
                'use-period': $('#use-period').val() // 学生の利用期限
            }
        })
        .done(function(data) {
                alert("ユーザー情報が作成されました");
                window.location.reload();    // ページをリロードして変更を反映
                console.log(data); // サーバーからのレスポンス内容を確認
            // エラーが発生した場合は何も表示しないようにする
            console.log('通信成功');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("通信失敗");
            console.log("ステータス: " + textStatus); // エラーステータス
            console.log("エラーメッセージ: " + errorThrown); // エラーの詳細
            console.log(jqXHR); // jqXHR オブジェクトには詳細なエラー情報が含まれる
        });
        

        
        
    });
});
