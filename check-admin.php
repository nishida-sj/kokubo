<?php
// 超シンプルな管理画面チェック
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Check</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        pre { background: white; padding: 15px; border-left: 4px solid #2E7D32; overflow-x: auto; }
        .ok { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>管理画面ファイルチェック</h1>

    <h2>1. 基本パス情報</h2>
    <pre><?php
echo "現在のディレクトリ: " . getcwd() . "\n";
echo "__DIR__: " . __DIR__ . "\n";
    ?></pre>

    <h2>2. app/Controllers/Admin ディレクトリチェック</h2>
    <pre><?php
$adminDir = __DIR__ . '/app/Controllers/Admin';
echo "パス: " . $adminDir . "\n";
if (file_exists($adminDir)) {
    echo "<span class='ok'>✅ 存在する</span>\n\n";
    echo "ファイル一覧:\n";
    $files = scandir($adminDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $fullPath = $adminDir . '/' . $file;
            $size = filesize($fullPath);
            echo "  - " . $file . " (" . number_format($size) . " bytes)\n";
        }
    }
} else {
    echo "<span class='error'>❌ 存在しない</span>\n";
}
    ?></pre>

    <h2>3. WorksController.php チェック</h2>
    <pre><?php
$worksController = __DIR__ . '/app/Controllers/Admin/WorksController.php';
echo "パス: " . $worksController . "\n";
if (file_exists($worksController)) {
    echo "<span class='ok'>✅ 存在する</span>\n";
    echo "サイズ: " . number_format(filesize($worksController)) . " bytes\n";
    echo "読み込み可能: " . (is_readable($worksController) ? 'Yes' : 'No') . "\n";

    // ファイルの最初の20行を表示
    echo "\n最初の20行:\n";
    echo "---\n";
    $lines = file($worksController);
    for ($i = 0; $i < min(20, count($lines)); $i++) {
        echo ($i + 1) . ": " . htmlspecialchars($lines[$i]);
    }
    echo "---\n";
} else {
    echo "<span class='error'>❌ 存在しない</span>\n";
}
    ?></pre>

    <h2>4. その他の管理画面ファイル</h2>
    <pre><?php
$files = [
    'LoginController.php' => __DIR__ . '/app/Controllers/Admin/LoginController.php',
    'DashboardController.php' => __DIR__ . '/app/Controllers/Admin/DashboardController.php',
    'ContactsController.php' => __DIR__ . '/app/Controllers/Admin/ContactsController.php',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    $icon = $exists ? "✅" : "❌";
    $class = $exists ? "ok" : "error";
    $size = $exists ? " (" . number_format(filesize($path)) . " bytes)" : "";
    echo "<span class='$class'>$icon</span> $name$size\n";
}
    ?></pre>

    <h2>5. PHPバージョン</h2>
    <pre><?php
echo "PHP Version: " . phpversion() . "\n";
echo "PHP SAPI: " . php_sapi_name() . "\n";
    ?></pre>

    <hr>
    <p><a href="/">トップページに戻る</a></p>
</body>
</html>
