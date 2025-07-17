from flask import Flask, request, jsonify
import requests

app = Flask(__name__)
SLACK_WEBHOOK = 'https://hooks.slack.com/services/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'  # Replace with your real webhook

@app.route('/slack-relay', methods=['POST'])
def slack_relay():
    data = request.get_json()
    
    # Format a simple Slack message
    message = {
        "text": f"🔔 Alert Received:\n{data}"
    }

    response = requests.post(SLACK_WEBHOOK_URL, json=message)

    if response.status_code == 200:
        return jsonify({"status": 200}), 200
    else:
        return jsonify({"status": response.status_code, "message": response.text}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
