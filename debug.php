<?php
// 一時的なデバッグ用ファイル - エラーの詳細を確認
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<h2>Debug Information</h2>";

// PHPバージョン確認
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// パス確認
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// ディレクトリ存在確認
$basePath = __DIR__;
echo "<p><strong>Base Path:</strong> " . $basePath . "</p>";

$dirs = [
    'app' => $basePath . '/app',
    'config' => $basePath . '/config',
    'uploads' => $basePath . '/uploads',
    'assets' => $basePath . '/assets',
    'vendor' => $basePath . '/vendor'
];

foreach ($dirs as $name => $path) {
    $exists = is_dir($path) ? "✓ EXISTS" : "✗ MISSING";
    echo "<p><strong>{$name} directory:</strong> {$path} - {$exists}</p>";
}

// config.phpの読み込みテスト
echo "<h3>Config.php Test</h3>";
$configPath = $basePath . '/config/config.php';
if (file_exists($configPath)) {
    echo "<p>config.php exists at: {$configPath}</p>";
    try {
        include_once $configPath;
        echo "<p>✓ config.php loaded successfully</p>";

        // 重要な定数チェック
        $constants = ['APP_NAME', 'SITE_URL', 'DB_HOST', 'DB_NAME', 'DB_USER'];
        foreach ($constants as $const) {
            if (defined($const)) {
                $value = constant($const);
                if ($const === 'DB_PASS') {
                    $value = str_repeat('*', strlen($value));
                }
                echo "<p><strong>{$const}:</strong> {$value}</p>";
            } else {
                echo "<p><strong>{$const}:</strong> NOT DEFINED</p>";
            }
        }
    } catch (Exception $e) {
        echo "<p>✗ Error loading config.php: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>✗ config.php not found at: {$configPath}</p>";
}

// データベース接続テスト
echo "<h3>Database Connection Test</h3>";
if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "<p>✓ Database connection successful</p>";

        // テーブル存在確認
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><strong>Tables found:</strong> " . implode(', ', $tables) . "</p>";

    } catch (PDOException $e) {
        echo "<p>✗ Database connection failed: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>✗ Database constants not defined</p>";
}

// .htaccessチェック
echo "<h3>Htaccess Check</h3>";
$htaccessPath = $basePath . '/.htaccess';
if (file_exists($htaccessPath)) {
    echo "<p>✓ .htaccess exists</p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($htaccessPath)) . "</pre>";
} else {
    echo "<p>✗ .htaccess not found</p>";
}

// エラーログの場所を表示
echo "<h3>Error Log Information</h3>";
echo "<p><strong>Error Log:</strong> " . ini_get('error_log') . "</p>";
echo "<p><strong>Log Errors:</strong> " . (ini_get('log_errors') ? 'ON' : 'OFF') . "</p>";

?>