function deleteRoom(button) {
    var classroomID = button.getAttribute("data-classroomID");
    var roomName = button.getAttribute("data-room-name"); // 教室番号を取得
    console.log("data-room-name:", button.getAttribute("data-room-name"));

    // 確認ダイアログを表示
    if (roomName) {
        var confirmation = confirm(`教室「${roomName}」の鍵を本当に削除しますか？`);
    } else {
        console.error("教室名が取得できませんでした。");
        return; // 教室名が取得できなかった場合は処理を中断
    }

    if (!confirmation) {
        return; // ユーザーがキャンセルした場合、処理を中断
    }

    console.log("classroomID:", classroomID, "roomName:", roomName);
    
    // Ajaxリクエストを送信
    $.ajax({
        type: "POST",
        url: "php/roomDelete.php",
        data: { classroomID: classroomID },
        success: function(response) {
            try {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    alert(jsonResponse.message); // 成功メッセージを表示
                    window.location.reload();    // ページをリロードして変更を反映
                } else {
                    alert("エラー: " + jsonResponse.message); // エラー内容を表示
                }
            } catch (e) {
                alert("不明なエラーが発生しました。"); // JSON解析に失敗した場合
            }
        },
        error: function(xhr) {
            alert("リクエストエラー: " + xhr.responseText); // Ajaxリクエスト自体のエラー時
        }
    });
}
