import datetime
import base64
import requests
import json
from Crypto.Hash import CMAC
from Crypto.Cipher import AES
from Crypto.Hash import BLAKE2s

import sys

# 必要な情報
api_key = 'O7t5cumA9cTYKJpFDo4eC0BxlMBuh823la2zlph0'  # API Key
# コマンド (toggle=88, lock=82, unlock=83)
cmd = 82  # 例 施錠
history = 'test_history'  # 操作の履歴情報
base64_history = base64.b64encode(history.encode('utf-8')).decode()

if len(sys.argv) < 3:
    print("デバイスコードとシークレットコードが指定されていません。")
    sys.exit(1)

uuid = sys.argv[1]  # devCode (UUID)
secret_key = sys.argv[2]  # secCode (秘密鍵)

# リクエストヘッダー
headers = {'x-api-key': api_key}

# AES-CMAC 署名生成
cmac = CMAC.new(bytes.fromhex(secret_key), ciphermod=AES)
timestamp = int(datetime.datetime.now().timestamp())  # 現在のタイムスタンプ (秒単位)
message = timestamp.to_bytes(4, byteorder='little')  # タイムスタンプをリトルエンディアンのバイト配列に変換
message = message[1:]  # 最上位バイトを除外して3バイトにする

# 署名生成
cmac.update(message)  # ここでmessageを使用
sign = cmac.hexdigest()

# リクエストボディ
body = {
    'cmd': cmd,
    'history': base64_history,
    'sign': sign
}

# Sesame APIへのPOSTリクエスト
url = f'https://app.candyhouse.co/api/sesame2/{uuid}/cmd'
response = requests.post(url, data=json.dumps(body), headers=headers)

if response.status_code == 200:
    print("Success:", response.text)
else:
    print("Error:", response.status_code, response.text)