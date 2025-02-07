import requests
import sys
from datetime import datetime, timedelta
import math

# コマンドライン引数でモデル番号を取得
sesame2_uuid = sys.argv[1]  # PHPから渡されたモデル番号



api_key = 'O7t5cumA9cTYKJpFDo4eC0BxlMBuh823la2zlph0'  # API Key

def fetch_sesame_status(api_key, sesame2_uuid):
    # GETリクエストのURL
    url = f'https://app.candyhouse.co/api/sesame2/{sesame2_uuid}'
    headers = {'x-api-key': api_key}

    try:
        # リクエスト送信
        response = requests.get(url, headers=headers)
        response.raise_for_status()
        return response.json()
    except requests.exceptions.RequestException as e:
        return {"error": str(e)}

if __name__ == "__main__":
    # ステータスを取得
    status = fetch_sesame_status(api_key, sesame2_uuid)

    if 'error' not in status:
        # ステータスを整形
        battery_percentage = f"{status['batteryPercentage']}%"
        position = math.floor(status['position'] * 0.3515625)  # 小数点以下切り捨て
        ch_sesame_status = status['CHSesame2Status']

        # タイムスタンプを日本時間に変換
        utc_timestamp = datetime.fromtimestamp(status['timestamp'])
        jst_timestamp = utc_timestamp + timedelta(hours=9)
        timestamp = jst_timestamp.strftime('%Y/%m/%d %H:%M:%S')

        # 結果を出力
        print(f"{battery_percentage}")
        # print(f"{position}°")
        # print({ch_sesame_status})
        # print(f"{timestamp}")


    else:
        # エラーメッセージを出力
        print(f"エラー: {status['error']}")
        
