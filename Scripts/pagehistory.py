import requests
from datetime import datetime, timedelta
import json

# 必要な情報
api_key = 'O7t5cumA9cTYKJpFDo4eC0BxlMBuh823la2zlph0'  # API Key
sesame2_uuid = '11200416-0103-0702-9000-E400FFFFFFFF'  # SesameデバイスのUUID

# タイプとメッセージのマッピング
type_messages = {
    0: "なし",
    1: "サミデバイスが施錠のBLEコマンドを受付ました。",
    2: "セサミデバイスが解錠のBLEコマンドを受付ました。",
    3: "セサミデバイスの内部時計が校正された。",
    4: "オートロックの設定が変更されました。",
    5: "施解錠角度の設定が変更されました。",
    6: "セサミデバイスがオートロックしました。",
    7: "手動で施錠 (下記ケース2またケース3からケース1になった場合)",
    8: "手動で解錠 (下記ケース1またケース3からケース2になった場合)",
    9: "解錠の範囲または施錠の範囲から、サムターンに動きがあった場合（下記ケース1からケース3になった場合、またはケース2からケース3になった場合）",
    10: "モーターが確実に施錠しました。",
    11: "モーターが確実に解錠しました。",
    12: "モーターが施解錠の途中に失敗しました。",
    13: "セサミデバイスが発信しているBLEアドバタイジングのIntervalとTXPowerの設定が変更されました。",
    14: "Wifiモジュールを経由してセサミデバイスを施錠しました。",
    15: "Wifiモジュールを経由してセサミデバイスを解錠しました。",
    16: "Web APIを経由してセサミデバイスを施錠しました。",
    17: "Web APIを経由してセサミデバイスを解錠しました。",
}

def fetch_sesame_history(api_key, sesame2_uuid):
    url = f'https://app.candyhouse.co/api/sesame2/{sesame2_uuid}/history?page=0&lg=10'
    headers = {
        'x-api-key': api_key
    }

    try:
        response = requests.get(url, headers=headers)
        response.raise_for_status()
        return response.json()
    
    except requests.exceptions.RequestException as e:
        return {"error": str(e)}

if __name__ == "__main__":
    history = fetch_sesame_history(api_key, sesame2_uuid)

    if 'error' not in history:
        formatted_history = []

        for record in history:
            record_info = {
                'type': record['type'],
                'message': type_messages.get(record['type'], "未知のタイプ"),  # タイプに基づくメッセージ
                'timestamp': datetime.fromtimestamp(record['timeStamp'] / 1000).strftime('%Y/%m/%d %H:%M:%S'),
                'historyTag': record.get('historyTag', 'No Tag'),
                'recordID': record['recordID'],
                'parameter': record.get('parameter', 'No Parameter')
            }
            formatted_history.append(record_info)

        # JSON形式で出力
        print(json.dumps(formatted_history, ensure_ascii=False))
    else:
        print(json.dumps({"error": history['error']}))