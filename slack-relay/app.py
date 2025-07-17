from flask import Flask, request, jsonify
import requests
import os
import traceback

app = Flask(__name__)

SLACK_WEBHOOK_URL = os.getenv('SLACK_WEBHOOK_URL')
TEAMS_WEBHOOK_URL = os.getenv('TEAMS_WEBHOOK_URL')

@app.route('/slack-relay', methods=['POST'])
def slack_relay():
    try:
        data = request.get_json(force=True)
        if not data:
            return jsonify({"error": "No data received"}), 400

        message = "*Alertmanager Alert:*\n"
        for alert in data.get("alerts", []):
            message += f"• *{alert.get('status')}* - {alert['labels'].get('alertname')} - {alert['annotations'].get('summary', 'No summary')}\n"

        slack_payload = {"text": message}
        teams_payload = {
            "@type": "MessageCard",
            "@context": "http://schema.org/extensions",
            "summary": "Alertmanager Alert",
            "themeColor": "FF0000",
            "title": "Alertmanager Alert",
            "text": message.replace("*", "")
        }

        slack_res = requests.post(SLACK_WEBHOOK_URL, json=slack_payload)
        teams_res = requests.post(TEAMS_WEBHOOK_URL, json=teams_payload)

        return jsonify({
            "slack_status": slack_res.status_code,
            "teams_status": teams_res.status_code
        })

    except Exception as e:
        print("⚠️ Exception occurred:")
        traceback.print_exc()
        return jsonify({"error": str(e)}), 500

