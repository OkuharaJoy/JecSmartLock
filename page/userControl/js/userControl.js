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
    $('#registerButton').on('click', function() {
        var role = $('#role').val();
        var name = $('#name').val();
        var email = $('#mail').val();
        var password = $('#password').val();
        var confirmPassword = $('#password-confirm').val();
        var phone = $('#edit-phone1').val();
        var openRoomNumber = $('#open-room-number').val(); // 正しいIDに修正

        var usePeriod = $('#use-period').val();

        // バリデーションフラグ
        var isValid = true;

        // 名前のバリデーション (ひらがな・漢字・英語のみ)
        var nameRegex = /^[\u3040-\u309F\u4E00-\u9FFFa-zA-Z]+$/;
        if (name.trim() === "") {
            alert("名前は必須です。");
            isValid = false;
        } else if (!nameRegex.test(name)) {
            alert("名前はひらがな、漢字、英字のみ入力可能です。");
            isValid = false;
        }

        // メールアドレスのバリデーション
        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            alert("正しいメールアドレスを入力してください。");
            isValid = false;
        }

        // パスワードの一致確認
        if (password !== confirmPassword) {
            alert("パスワードが一致しません。");
            isValid = false;
        }

        // 携帯番号のバリデーション（10桁または11桁の数字）
        var phoneRegex = /^\d{10,11}$/;
        if (role === '1' || role === '2') {
            if (!phoneRegex.test(phone)) {
                alert("携帯番号は10桁または11桁の数字を入力してください。");
                isValid = false;
            }
        }

        // 学生の場合、施錠号室と利用期限のチェック
        if (role === '3') {
            if (!openRoomNumber || !usePeriod) {
                alert("施錠号室と利用期限は必須です。");
                isValid = false;
            }
        }

        // バリデーションが成功した場合のみフォーム送信
        if (!isValid) return;

        // AJAXでサーバーにデータ送信
        $.ajax({
            url: 'php/userCreate.php', // サーバー側のPHPファイル
            type: 'POST', // 送信方法
            datatype: 'json', // 受け取るデータ形式
            data: {
                userId: $('#edit-user1').val(),
                name: name,
                mail: email,
                password: password,
                tel: phone,
                role: role,
                'open-room-number': openRoomNumber,
                'use-period': usePeriod
            }
        })
        .done(function(data) {
            alert("ユーザー情報が作成されました");
            window.location.reload(); // ページをリロード
            console.log(data); // レスポンス内容
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("通信失敗");
            console.log("ステータス: " + textStatus);
            console.log("エラーメッセージ: " + errorThrown);
            console.log(jqXHR);
        });
    });
});
