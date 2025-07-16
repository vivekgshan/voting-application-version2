<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB credentials
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'votingdb';
$user = getenv('DB_USER') ?: 'votinguser';
$pass = getenv('DB_PASS') ?: 'votingpass';

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vote = $_POST['vote'] ?? '';
    if (!empty($vote)) {
        $stmt = $conn->prepare("INSERT INTO votes (option_name) VALUES (?)");
        $stmt->bind_param("s", $vote);
        $message = $stmt->execute() ? "✅ Thank you for voting!" : "❌ Error recording vote.";
        $stmt->close();
    }
}

// Fetch vote counts
$results = [];
$res = $conn->query("SELECT option_name, COUNT(*) as count FROM votes GROUP BY option_name");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $results[$row['option_name']] = $row['count'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voting App</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>🗳️ Vote for your favorite option</h1>

    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <label><input type="radio" name="vote" value="Option A" required> Option A</label><br>
        <label><input type="radio" name="vote" value="Option B"> Option B</label><br>
        <label><input type="radio" name="vote" value="Option C"> Option C</label><br>
        <button type="submit">Submit Vote</button>
    </form>

    <h2>📊 Current Vote Counts:</h2>

    <?php if (!empty($results)): ?>
        <ul>
            <?php foreach ($results as $option => $count): ?>
                <li><strong><?php echo htmlspecialchars($option); ?>:</strong> <?php echo $count; ?> votes</li>
            <?php endforeach; ?>
        </ul>

        <canvas id="voteChart" width="400" height="400"></canvas>
        <script>
            const ctx = document.getElementById('voteChart').getContext('2d');
            const voteChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_keys($results)); ?>,
                    datasets: [{
                        label: 'Vote Count',
                        data: <?php echo json_encode(array_values($results)); ?>,
                        backgroundColor: [
                            '#4e79a7',
                            '#f28e2c',
                            '#e15759',
                            '#76b7b2',
                            '#59a14f',
                            '#edc948'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    <?php else: ?>
        <p>No votes recorded yet.</p>
    <?php endif; ?>
</body>
</html>
