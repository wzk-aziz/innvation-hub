<?php
echo "<h1>PHP Error Log</h1>";
echo "<h2>Recent Error Log Entries:</h2>";

// Try to find PHP error log
$possibleLogs = [
    'php_errors.log',
    '../php_errors.log',
    '../../php_errors.log',
    '/tmp/php_errors.log',
    '/var/log/php_errors.log'
];

$logFound = false;

foreach ($possibleLogs as $logPath) {
    if (file_exists($logPath)) {
        echo "<h3>Log file: $logPath</h3>";
        $logContent = file_get_contents($logPath);
        echo "<pre>" . htmlspecialchars($logContent) . "</pre>";
        $logFound = true;
        break;
    }
}

if (!$logFound) {
    echo "<p>Error log file not found. Logs might be displayed in the PHP development server console.</p>";
    echo "<p>Check the terminal where you started 'php -S localhost:8000' for error messages.</p>";
}

echo "<h2>Also check server terminal output after attempting delete operation.</h2>";
?>
