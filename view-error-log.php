<?php
// エラーログ表示ページ
header('Content-Type: text/html; charset=UTF-8');

// セキュリティ: 本番環境では削除してください
$allowedIPs = ['127.0.0.1', '::1']; // ローカルのみ許可する場合
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
//     die('Access denied');
// }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Error Log Viewer</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        pre { background: white; padding: 15px; border-left: 4px solid #d32f2f; overflow-x: auto; max-height: 600px; overflow-y: auto; }
        .error { color: #d32f2f; }
        .info { color: #1976d2; }
        .refresh { display: inline-block; padding: 10px 20px; background: #1976d2; color: white; text-decoration: none; border-radius: 4px; margin-bottom: 20px; }
        .clear { display: inline-block; padding: 10px 20px; background: #d32f2f; color: white; text-decoration: none; border-radius: 4px; margin-bottom: 20px; margin-left: 10px; }
    </style>
</head>
<body>
    <h1>PHP Error Log</h1>

    <a href="view-error-log.php" class="refresh">🔄 Refresh</a>

    <?php if (isset($_GET['clear'])): ?>
        <?php
        // エラーログをクリア（注意：PHPエラーログの場所を確認）
        $errorLogPath = ini_get('error_log');
        if ($errorLogPath && file_exists($errorLogPath)) {
            file_put_contents($errorLogPath, '');
            echo '<p class="info">Error log cleared!</p>';
        }
        ?>
    <?php endif; ?>

    <h2>PHP Error Log Location</h2>
    <pre><?php
    echo "error_log setting: " . (ini_get('error_log') ?: 'not set') . "\n";
    echo "display_errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "\n";
    echo "error_reporting: " . error_reporting() . "\n";
    ?></pre>

    <h2>Recent Error Log (last 100 lines)</h2>
    <pre><?php
    $errorLogPath = ini_get('error_log');

    if ($errorLogPath && file_exists($errorLogPath)) {
        echo "Reading from: " . $errorLogPath . "\n";
        echo "File size: " . number_format(filesize($errorLogPath)) . " bytes\n";
        echo "Last modified: " . date('Y-m-d H:i:s', filemtime($errorLogPath)) . "\n";
        echo "\n" . str_repeat('=', 80) . "\n\n";

        $lines = file($errorLogPath);
        if ($lines) {
            $recentLines = array_slice($lines, -100);
            echo htmlspecialchars(implode('', $recentLines));
        } else {
            echo "Log file is empty.";
        }
    } else {
        echo "Error log file not found at: " . $errorLogPath . "\n\n";
        echo "Alternative locations to check:\n";
        echo "- /var/log/php_errors.log\n";
        echo "- /var/log/apache2/error.log\n";
        echo "- /var/log/httpd/error_log\n";
        echo "- " . __DIR__ . "/storage/logs/error.log\n";

        // storage/logs をチェック
        $storageLog = __DIR__ . '/storage/logs/error.log';
        if (file_exists($storageLog)) {
            echo "\n" . str_repeat('=', 80) . "\n";
            echo "Found log in storage/logs/error.log:\n";
            echo str_repeat('=', 80) . "\n\n";
            $lines = file($storageLog);
            $recentLines = array_slice($lines, -100);
            echo htmlspecialchars(implode('', $recentLines));
        }
    }
    ?></pre>

    <h2>Manual Log Check</h2>
    <form method="post" style="margin-top: 20px;">
        <label>Custom log file path:</label><br>
        <input type="text" name="custom_log" value="/var/log/php_errors.log" style="width: 400px; padding: 8px; margin-top: 8px;">
        <button type="submit" style="padding: 8px 20px; margin-left: 8px;">View</button>
    </form>

    <?php if (isset($_POST['custom_log'])): ?>
        <h3>Custom Log File</h3>
        <pre><?php
        $customPath = $_POST['custom_log'];
        if (file_exists($customPath)) {
            echo "Reading from: " . $customPath . "\n\n";
            $lines = file($customPath);
            $recentLines = array_slice($lines, -100);
            echo htmlspecialchars(implode('', $recentLines));
        } else {
            echo "File not found: " . $customPath;
        }
        ?></pre>
    <?php endif; ?>

</body>
</html>
