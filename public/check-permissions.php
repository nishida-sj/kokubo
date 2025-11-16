<?php
// 超シンプルな権限チェックページ（config不要）
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>権限チェック</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #f5f5f5;
            line-height: 1.8;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2E7D32;
            border-bottom: 3px solid #2E7D32;
            padding-bottom: 10px;
        }
        h2 {
            color: #333;
            margin-top: 30px;
            background: #f9f9f9;
            padding: 10px;
            border-left: 4px solid #2E7D32;
        }
        pre {
            background: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #2196F3;
            overflow-x: auto;
            font-size: 0.95em;
        }
        .ok { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #FF9800; font-weight: bold; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #2E7D32;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 権限チェック（設定ファイル不要版）</h1>

        <h2>1. 基本情報</h2>
        <pre><?php
echo "現在のディレクトリ (__DIR__): " . __DIR__ . "\n";
echo "PHPバージョン: " . PHP_VERSION . "\n";
echo "実行ユーザー: " . get_current_user() . "\n";
if (function_exists('posix_getuid')) {
    echo "UID: " . posix_getuid() . "\n";
    echo "GID: " . posix_getgid() . "\n";
}
        ?></pre>

        <h2>2. ディレクトリ構造チェック</h2>
        <table>
            <tr>
                <th>パス</th>
                <th>存在</th>
                <th>パーミッション</th>
                <th>読取</th>
                <th>書込</th>
            </tr>
            <?php
            $checkPaths = [
                __DIR__,
                __DIR__ . '/config',
                __DIR__ . '/app',
                __DIR__ . '/uploads',
                __DIR__ . '/uploads/works',
                __DIR__ . '/uploads/works/thumbs',
            ];

            foreach ($checkPaths as $path) {
                $exists = file_exists($path);
                $isDir = is_dir($path);
                $readable = is_readable($path);
                $writable = is_writable($path);
                $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';

                echo '<tr>';
                echo '<td><code>' . htmlspecialchars($path) . '</code></td>';
                echo '<td>' . ($exists && $isDir ? '<span class="ok">✅ Dir</span>' : ($exists ? '<span class="warning">⚠️ File</span>' : '<span class="error">❌ なし</span>')) . '</td>';
                echo '<td><code>' . $perms . '</code></td>';
                echo '<td>' . ($readable ? '<span class="ok">✅</span>' : '<span class="error">❌</span>') . '</td>';
                echo '<td>' . ($writable ? '<span class="ok">✅</span>' : '<span class="error">❌</span>') . '</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <h2>3. uploadsディレクトリの内容</h2>
        <pre><?php
$uploadsDir = __DIR__ . '/uploads';
if (is_dir($uploadsDir)) {
    echo "📁 uploadsディレクトリの内容:\n\n";

    function listDirectory($dir, $indent = '') {
        $items = @scandir($dir);
        if ($items === false) {
            echo $indent . "❌ 読み取り不可\n";
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $path = $dir . '/' . $item;
            $perms = substr(sprintf('%o', fileperms($path)), -4);

            if (is_dir($path)) {
                echo $indent . "📁 " . $item . " (パーミッション: " . $perms . ")\n";
                listDirectory($path, $indent . '  ');
            } else {
                $size = filesize($path);
                echo $indent . "📄 " . $item . " (" . number_format($size) . " bytes, パーミッション: " . $perms . ")\n";
            }
        }
    }

    listDirectory($uploadsDir);
} else {
    echo "❌ uploadsディレクトリが存在しません: " . $uploadsDir . "\n";
}
        ?></pre>

        <h2>4. 書き込みテスト</h2>
        <pre><?php
$testDir = __DIR__ . '/uploads/works';
if (is_dir($testDir)) {
    $testFile = $testDir . '/test-' . time() . '.txt';
    echo "テストファイル: " . $testFile . "\n\n";

    $content = "テスト書き込み: " . date('Y-m-d H:i:s');
    $result = @file_put_contents($testFile, $content);

    if ($result !== false) {
        echo "✅ 書き込み成功！\n";
        echo "書き込みバイト数: " . $result . "\n";

        if (file_exists($testFile)) {
            echo "✅ ファイル存在確認: OK\n";
            $readContent = file_get_contents($testFile);
            echo "読み取り内容: " . $readContent . "\n";

            // クリーンアップ
            if (@unlink($testFile)) {
                echo "✅ テストファイル削除: OK\n";
            } else {
                echo "⚠️ テストファイル削除: 失敗（手動削除が必要）\n";
            }
        } else {
            echo "❌ ファイルが作成されませんでした\n";
        }
    } else {
        echo "❌ 書き込み失敗\n";
        $error = error_get_last();
        if ($error) {
            echo "PHPエラー: " . $error['message'] . "\n";
        }
        echo "\n考えられる原因:\n";
        echo "- ディレクトリに書き込み権限がない\n";
        echo "- ディスク容量不足\n";
        echo "- SELinux/AppArmorなどのセキュリティ設定\n";
    }
} else {
    echo "❌ テスト対象ディレクトリが存在しません: " . $testDir . "\n";
}
        ?></pre>

        <h2>5. PHP設定</h2>
        <pre><?php
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "file_uploads: " . (ini_get('file_uploads') ? 'ON' : 'OFF') . "\n";
echo "upload_tmp_dir: " . (ini_get('upload_tmp_dir') ?: 'default') . "\n";
        ?></pre>

        <h2>6. GDライブラリ</h2>
        <pre><?php
if (function_exists('gd_info')) {
    $gd = gd_info();
    foreach ($gd as $key => $value) {
        echo str_pad($key . ':', 30) . ($value === true ? '✅ Yes' : ($value === false ? '❌ No' : $value)) . "\n";
    }
} else {
    echo "❌ GDライブラリが利用できません\n";
}
        ?></pre>

        <div style="margin-top: 40px; padding: 20px; background: #f0f0f0; border-radius: 8px; text-align: center;">
            <a href="/debug-images.php" style="margin: 0 10px;">← 画像デバッグ</a> |
            <a href="/test-upload.php" style="margin: 0 10px;">アップロードテスト</a> |
            <a href="/admin/works" style="margin: 0 10px;">管理画面</a>
        </div>
    </div>
</body>
</html>
