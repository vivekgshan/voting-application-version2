from flask import Flask, request, jsonify
import requests

app = Flask(__name__)
SLACK_WEBHOOK = 'https://hooks.slack.com/services/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'  # Replace with your real webhook

@app.route('/slack-relay', methods=['POST'])
def slack_relay():
    data = request.get_json()
    if not data:
        return jsonify({"error": "No data"}), 400

    message = "*Alertmanager Alert:*\n"
    for alert in data.get("alerts", []):
        message += f"• *{alert.get('status')}* - {alert['labels'].get('alertname')} - {alert['annotations'].get('summary', 'No summary')}\n"

    payload = {"text": message}
    response = requests.post(SLACK_WEBHOOK, json=payload)
    return jsonify({"status": response.status_code}), response.status_code
