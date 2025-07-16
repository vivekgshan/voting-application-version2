# voting-application-v2

# 🗳️ PHP Voting Application with Monitoring

This project sets up a simple PHP-based Voting App and includes a complete monitoring stack using:

- Prometheus (metrics collection)
- Grafana (visualization)
- Node Exporter (host metrics)
- MySQL Exporter (database metrics)
- Alertmanager (alert routing via Teams/Slack)
- Docker Compose for orchestration

---

## 📦 Project Structure

voting-Project/
│
├── docker-compose.yml
├── init.sql # MySQL initial schema
├── frontend/ # PHP voting app
│ └── metrics.php # Prometheus metrics endpoint
├── monitoring/
│ ├── prometheus.yml
│ ├── alert_rules.yml
│ └── alertmanager/
│ └── config.yml
├── grafana/
│ ├── dashboards/
│ │ └── voting-dashboard.json
│ └── provisioning/
│ ├── datasources/
│ │ └── prometheus-datasource.yml
│ └── dashboards/
│ └── dashboards.yaml



## 🚀 Getting Started

### 1️⃣ Clone the repository

```bash
git clone https://github.com/your-repo/voting-Project.git
cd voting-Project
2️⃣ Create a Docker network

docker network create monitoring-net
3️⃣ Build and Start Containers

docker compose up -d --build

🔍 Access the Services
Service	URL	Default Credentials
Voting App	http://localhost	—
Grafana	http://localhost:3000	admin / admin
Prometheus	http://localhost:9090	—
Alertmanager	http://localhost:9093	—

📊 Grafana Dashboard
Dashboards are auto-provisioned from grafana/dashboards/

Includes:

PHP voting app metrics (via metrics.php)

MySQL metrics (via mysqld-exporter)

Node system metrics (via node-exporter)

Prometheus metrics (internal)

🔔 Alerting
Alertmanager is configured to route alerts to Slack or Microsoft Teams

Configure your webhook URL in monitoring/alertmanager/config.yml

Example for Slack:

receivers:
  - name: 'slack'
    slack_configs:
      - api_url: 'https://hooks.slack.com/services/XXXXX/YYYYY/ZZZZZ'
        channel: '#alerts'

📈 Prometheus Targets
Prometheus scrapes the following:

mysql-db:9104 → MySQL metrics

voting-ui:80/metrics.php → Custom app metrics

node-exporter:9100 → Host metrics

prometheus:9090 → Self metrics

grafana:3000 → Health endpoints

⚠️ Troubleshooting
🛑 MySQL "No space left on device"
Run:

df -h
docker system prune -a
Ensure disk space is available.

🔧 Rebuilding Voting App
If frontend changes:

docker compose build voting-ui
docker compose up -d
📌 Notes
Built for local development and monitoring demos

Uses monitoring-net Docker network (shared between app and monitoring)

📜 License
MIT License



Let me know if you'd like this `README.md` 
