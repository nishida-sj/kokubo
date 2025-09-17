<?php
// 簡易テスト用ファイル
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PHP Test Page</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Current Directory: " . __DIR__ . "</p>";

// 設定ファイル読み込みテスト
echo "<h2>Config Test</h2>";
$configPath = __DIR__ . '/config/config.php';
echo "<p>Config path: $configPath</p>";

if (file_exists($configPath)) {
    echo "<p>✓ Config file exists</p>";
    try {
        require_once $configPath;
        echo "<p>✓ Config loaded successfully</p>";
        echo "<p>APP_NAME: " . (defined('APP_NAME') ? APP_NAME : 'NOT DEFINED') . "</p>";
        echo "<p>SITE_URL: " . (defined('SITE_URL') ? SITE_URL : 'NOT DEFINED') . "</p>";
    } catch (Exception $e) {
        echo "<p>✗ Config error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>✗ Config file not found</p>";
}

// クラス存在確認
echo "<h2>Class Test</h2>";
$classes = ['Router', 'Controller', 'Database'];
foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "<p>✓ $class class found</p>";
    } else {
        echo "<p>✗ $class class not found</p>";

        // ファイル存在確認
        $file = __DIR__ . "/app/$class.php";
        if (file_exists($file)) {
            echo "<p>  - File exists at: $file</p>";
            try {
                require_once $file;
                echo "<p>  - ✓ Successfully loaded</p>";
            } catch (Exception $e) {
                echo "<p>  - ✗ Load error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p>  - File not found at: $file</p>";
        }
    }
}

echo "<p><strong>Test completed at:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>