import requests
import sys
import math
from datetime import datetime, timedelta

api_key = "O7t5cumA9cTYKJpFDo4eC0BxlMBuh823la2zlph0"

def fetch_sesame_status(api_key, device_uuid):
    url = f'https://app.candyhouse.co/api/sesame2/{device_uuid}'
    headers = {'x-api-key': api_key}
    response = requests.get(url, headers=headers)
    if response.status_code != 200:
        raise Exception(f"APIリクエスト失敗: HTTP {response.status_code}")
    response.raise_for_status()
    return response.json()

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("デバイスコードが指定されていません。")
        sys.exit(1)

    device_code = sys.argv[1]

    try:
        status = fetch_sesame_status(api_key, device_code)

        battery_percentage = status['batteryPercentage']
        angle = status['position'] * 0.3515625
        position = math.floor(angle)
        ch_sesame_status = status['CHSesame2Status']
        utc_timestamp = datetime.fromtimestamp(status['timestamp'])
        jst_timestamp = utc_timestamp + timedelta(hours=9)

        print(f"{battery_percentage}%")
        print(f"{position}°")
        print(f"{ch_sesame_status}")
        print(jst_timestamp.strftime('%Y/%m/%d %H:%M:%S'))
    except Exception as e:
        print(f"エラー: {str(e)}")
        sys.exit(1)
