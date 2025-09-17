<?php
// 最もシンプルなテスト
echo "<h1>PHP動作テスト</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>現在時刻: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>このファイルが表示されれば、PHPは正常に動作しています。</p>";

// ディレクトリ存在確認
echo "<h2>ディレクトリ確認</h2>";
$dirs = ['app', 'config', 'uploads', 'assets'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    $exists = is_dir($path) ? '✓' : '✗';
    echo "<p>$dir: $exists ($path)</p>";
}

// 重要ファイル確認
echo "<h2>重要ファイル確認</h2>";
$files = [
    'config/config.php',
    'app/Router.php',
    'app/Controller.php',
    'app/Database.php',
    '.htaccess'
];
foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path) ? '✓' : '✗';
    echo "<p>$file: $exists</p>";
}
?>