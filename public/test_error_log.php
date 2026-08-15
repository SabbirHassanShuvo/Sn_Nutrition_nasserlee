<?php
// Print PHP configuration and the last lines of the PHP error log
echo "<h2>PHP Error Log Diagnostic</h2>";
echo "<strong>PHP Error Log Path:</strong> " . ini_get('error_log') . "<br><br>";

$logPath = ini_get('error_log');

if ($logPath && file_exists($logPath)) {
    echo "<h3>Last 30 entries in PHP Error Log:</h3>";
    $lines = file($logPath);
    $lastLines = array_slice($lines, -30);
    echo "<pre style='background:#f4f4f4; padding:15px; border:1px solid #ddd; overflow-x:auto; font-family:monospace;'>" . htmlspecialchars(implode("", $lastLines)) . "</pre>";
} else {
    echo "<div style='color:red; font-weight:bold;'>PHP Error log file does not exist, is empty, or is not readable at path: " . htmlspecialchars($logPath) . "</div>";
}
