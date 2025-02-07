import requests
from datetime import datetime, timedelta
import math

# 必要な情報
api_key = 'O7t5cumA9cTYKJpFDo4eC0BxlMBuh823la2zlph0'  # API Key
sesame2_uuid = '11200416-0103-0702-9000-E400FFFFFFFF'  # SesameデバイスのUUID

def fetch_sesame_history(api_key, sesame2_uuid):
    url = f'https://app.candyhouse.co/api/sesame2/{sesame2_uuid}/history?page=0&lg=20'
    headers = {
        'x-api-key': api_key
    }

    try:
        response = requests.get(url, headers=headers)
        #response.raise_for_history()
        response.raise_for_status()

        return response.json()
    
    except requests.exceptions.RequestException as e:
        return {"error": str(e)}


if __name__ == "__main__":
    history = fetch_sesame_history(api_key, sesame2_uuid)

    if 'error' not in history:
        for record in history:  # 複数履歴を処理
            type = record['type']

            utc_timestamp = datetime.fromtimestamp(record['timeStamp'] / 1000)
            jst_timestamp = utc_timestamp + timedelta(hours=9)
            timestamp = jst_timestamp.strftime('%Y/%m/%d %H:%M:%S')

            historyTag = record.get('historyTag', 'No Tag')  # タグがない場合のデフォルト値
            recordID = record['recordID']
            parameter = record.get('parameter', 'No Parameter')  # パラメータがない場合のデフォルト値

            print(f"Type: {type}")
            print(f"Timestamp: {timestamp}")
            # print(f"History Tag: {historyTag}")
            print(f"Record ID: {recordID}")
            # print(f"Parameter: {parameter}")
            print("-" * 40)  # 区切り線
    else:
        print(f"エラー: {history['error']}")

    


        