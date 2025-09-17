<?php
// 簡単なWebhookデプロイスクリプト
// サーバーに設置して、GitHubのWebhookから呼び出す

// セキュリティ用シークレットキー（GitHub Webhookで設定）
$secret = 'your-webhook-secret-key';

// GitHub Webhookの検証
$hub_signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
$payload = file_get_contents('php://input');

if (!hash_equals('sha1=' . hash_hmac('sha1', $payload, $secret), $hub_signature)) {
    http_response_code(403);
    die('Invalid signature');
}

// デプロイ処理
$deploy_dir = '/home/username/public_html';
$repo_url = 'https://github.com/nishida-sj/kokubo.git';

// ログファイル
$log_file = $deploy_dir . '/deploy.log';

function writeLog($message) {
    global $log_file;
    file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
}

try {
    writeLog('Deploy started');

    // Gitプル実行
    $commands = [
        "cd $deploy_dir",
        "git pull origin main",
        "composer install --no-dev --optimize-autoloader",
        "chmod 755 public/uploads",
    ];

    foreach ($commands as $command) {
        writeLog("Executing: $command");
        $output = shell_exec($command . ' 2>&1');
        writeLog("Output: $output");
    }

    writeLog('Deploy completed successfully');
    echo "Deploy successful!";

} catch (Exception $e) {
    writeLog('Deploy failed: ' . $e->getMessage());
    http_response_code(500);
    echo "Deploy failed: " . $e->getMessage();
}
?>