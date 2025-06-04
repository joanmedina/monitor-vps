<?php
$period = $_GET['period'] ?? 'day';
$seconds = [
    'day' => 86400,
    'week' => 604800,
    'month' => 2592000,
];
$interval = $seconds[$period] ?? $seconds['day'];

$dbPath = __DIR__ . '/data/metrics.sqlite';
if (!file_exists($dbPath)) {
    echo json_encode([]);
    exit;
}

$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->prepare('SELECT timestamp, cpu, ram_used, ram_free, disk_used, disk_free, rx, tx FROM metrics WHERE timestamp >= :from ORDER BY timestamp ASC');
$stmt->execute([':from' => time() - $interval]);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
