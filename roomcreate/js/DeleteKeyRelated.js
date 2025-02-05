function DeleteKeyRelated(button) {
    var K_classroomID = button.getAttribute("keyRelatedID");
    var K_roomName = button.getAttribute("DelKeyClassName");
    console.log("keyRelatedID:", button.getAttribute("keyRelatedID"), "DelKeyClassName", button.getAttribute("DelKeyClassName"));

    if (K_roomName) {
            var confirmation = confirm(`教室「${K_roomName}」を本当に削除しますか？`);
        } else {
            console.error("教室名が取得できませんでした。");
            return; // 教室名が取得できなかった場合は処理を中断
        }

        if (!confirmation) {
            return; // ユーザーがキャンセルした場合、処理を中断
        }

        console.log("Kcl", K_classroomID, "Kna", K_roomName);


        $.ajax({
            type: "POST",
            url: "php/DelKeyRelated.php",
            data: { K_classroomID: K_classroomID },
            success: function(response) {
                console.log("Response:", response);
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