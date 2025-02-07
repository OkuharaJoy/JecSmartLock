import requests
from datetime import datetime, timedelta
import math

# 必要な情報
api_key = 'O7t5cumA9cTYKJpFDo4eC0BxlMBuh823la2zlph0'  # API Key
sesame2_uuid = '11200416-0103-0702-9000-E400FFFFFFFF'  # SesameデバイスのUUID

def fetch_sesame_status(api_key, sesame2_uuid):
    # GETリクエストのURL
    url = f'https://app.candyhouse.co/api/sesame2/{sesame2_uuid}'

    # リクエストヘッダー
    headers = {
        'x-api-key': api_key
    }

    try:
        # GETリクエストの送信 
        response = requests.get(url, headers=headers)
        response.raise_for_status()

        # レスポンスの取得
        return response.json() # JSONレスポンスを返す

    except requests.exceptions.RequestException as e:
        # エラーメッセージを返す
        return {"error": str(e)}

if __name__ == "__main__":
    # ステータスを取得
    status = fetch_sesame_status(api_key, sesame2_uuid)

    # ステータスの整形
    if 'error' not in status:
        battery_percentage = status['batteryPercentage']
        angle = status['position'] * 0.3515625
        position = math.floor(angle)
        ch_sesame_status = status['CHSesame2Status']

        # タイムスタンプを日本時間に変換
        utc_timestamp = datetime.fromtimestamp(status['timestamp']) 
        jst_timestamp = utc_timestamp + timedelta(hours=9)

        # JSTでの表示形式
        timestamp = jst_timestamp.strftime('%Y/%m/%d %H:%M:%S')
        
        # 表示形式の整形
        print(f"{battery_percentage}%")
        print(f"{position}°")
        print(f"{ch_sesame_status}")
        print(f"{timestamp}")
    else:
        print(f"エラー: {status['error']}")
    
    
