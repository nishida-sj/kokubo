<?php
// エラーログ確認
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>エラーログ確認</h1>";

// PHPエラーログの場所を確認
$error_log = ini_get('error_log');
echo "<p><strong>Error Log Location:</strong> " . ($error_log ?: 'システムログ') . "</p>";

// エラーログファイルが存在する場合は内容を表示
if ($error_log && file_exists($error_log)) {
    echo "<h2>最新のエラーログ (最後の50行)</h2>";
    $lines = file($error_log);
    $recent_lines = array_slice($lines, -50);
    echo "<pre style='background:#f5f5f5; padding:10px; max-height:400px; overflow:auto;'>";
    foreach ($recent_lines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "<p>エラーログファイルが見つかりません。</p>";
}

// .htaccessの内容確認
echo "<h2>.htaccess 内容確認</h2>";
$htaccess_path = __DIR__ . '/.htaccess';
if (file_exists($htaccess_path)) {
    echo "<p>✓ .htaccess が存在します</p>";
    $content = file_get_contents($htaccess_path);
    echo "<pre style='background:#f5f5f5; padding:10px; max-height:300px; overflow:auto;'>";
    echo htmlspecialchars($content);
    echo "</pre>";
} else {
    echo "<p>✗ .htaccess が見つかりません</p>";
}

// Apache設定確認
echo "<h2>Apache設定確認</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $rewrite_enabled = in_array('mod_rewrite', $modules) ? '✓' : '✗';
    echo "<p>mod_rewrite: $rewrite_enabled</p>";
} else {
    echo "<p>Apache設定情報を取得できません</p>";
}
?>