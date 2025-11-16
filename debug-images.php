<?php
// 画像パスデバッグページ
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>画像パスデバッグ</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        pre { background: white; padding: 15px; border-left: 4px solid #2E7D32; overflow-x: auto; }
        .ok { color: green; }
        .error { color: red; }
        table { background: white; border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #2E7D32; color: white; }
        .img-preview { max-width: 200px; max-height: 150px; border: 1px solid #ddd; }
        .broken-img { background: #ffebee; color: #c62828; padding: 20px; text-align: center; }
    </style>
</head>
<body>
    <h1>画像パスデバッグ</h1>

    <h2>1. 定数確認</h2>
    <pre><?php
echo "SITE_URL: " . SITE_URL . "\n";
echo "UPLOAD_PATH: " . UPLOAD_PATH . "\n";
echo "UPLOAD_URL: " . UPLOAD_URL . "\n";
echo "PUBLIC_PATH: " . PUBLIC_PATH . "\n";
echo "\nsite_url('/uploads/works/test.jpg'): " . site_url('/uploads/works/test.jpg') . "\n";
echo "upload_url('works/test.jpg'): " . upload_url('works/test.jpg') . "\n";
    ?></pre>

    <h2>2. uploadsディレクトリの確認</h2>
    <pre><?php
$uploadsDir = PUBLIC_PATH . '/uploads';
echo "Uploadsディレクトリパス: " . $uploadsDir . "\n";
echo "存在: " . (is_dir($uploadsDir) ? "✅ Yes" : "❌ No") . "\n";

if (is_dir($uploadsDir)) {
    echo "\nサブディレクトリ:\n";
    $items = scandir($uploadsDir);
    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $path = $uploadsDir . '/' . $item;
            echo "  - " . $item;
            if (is_dir($path)) {
                echo " (ディレクトリ)\n";
                $files = scandir($path);
                $fileCount = count(array_filter($files, function($f) use ($path) {
                    return $f !== '.' && $f !== '..' && is_file($path . '/' . $f);
                }));
                echo "    ファイル数: " . $fileCount . "\n";
            } else {
                echo " (ファイル)\n";
            }
        }
    }
}
    ?></pre>

    <h2>3. データベースの実績データ</h2>
    <?php
    try {
        $db = Db::getInstance();
        $works = $db->fetchAll("SELECT id, title, main_image, created_at FROM works ORDER BY id DESC LIMIT 10");

        if (empty($works)) {
            echo '<p class="error">❌ データベースに実績データがありません</p>';
        } else {
            echo '<table>';
            echo '<tr><th>ID</th><th>タイトル</th><th>main_imageカラムの値</th><th>修正後のパス</th><th>プレビュー</th></tr>';

            foreach ($works as $work) {
                $originalPath = $work['main_image'];

                // 修正ロジック
                $fixedPath = $originalPath;
                if ($fixedPath && strpos($fixedPath, '/uploads/') === false && strpos($fixedPath, '/') === 0) {
                    $fixedPath = '/uploads' . $fixedPath;
                }

                $fullUrl = site_url($fixedPath);

                echo '<tr>';
                echo '<td>' . h($work['id']) . '</td>';
                echo '<td>' . h($work['title']) . '</td>';
                echo '<td><code>' . h($originalPath) . '</code></td>';
                echo '<td><code>' . h($fixedPath) . '</code><br><small>' . h($fullUrl) . '</small></td>';
                echo '<td>';

                if ($fixedPath) {
                    // 実際に画像を表示してみる
                    $serverPath = PUBLIC_PATH . $fixedPath;
                    if (file_exists($serverPath)) {
                        echo '<img src="' . h($fullUrl) . '" class="img-preview" alt="preview">';
                        echo '<br><small class="ok">✅ ファイル存在: ' . h($serverPath) . '</small>';
                    } else {
                        echo '<div class="broken-img">画像なし</div>';
                        echo '<small class="error">❌ ファイル不存在: ' . h($serverPath) . '</small>';
                    }
                } else {
                    echo '<div class="broken-img">パスなし</div>';
                }

                echo '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
    } catch (Exception $e) {
        echo '<p class="error">❌ データベースエラー: ' . h($e->getMessage()) . '</p>';
    }
    ?>

    <h2>4. uploadsディレクトリの実際のファイル</h2>
    <pre><?php
$worksDir = PUBLIC_PATH . '/uploads/works';
if (is_dir($worksDir)) {
    echo "✅ /uploads/works ディレクトリが存在します\n\n";
    echo "ファイル一覧:\n";
    $files = scandir($worksDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && is_file($worksDir . '/' . $file)) {
            $size = filesize($worksDir . '/' . $file);
            echo "  - " . $file . " (" . number_format($size) . " bytes)\n";
        }
    }
} else {
    echo "❌ /uploads/works ディレクトリが存在しません\n";
    echo "パス: " . $worksDir . "\n";
}
    ?></pre>

    <h2>5. 画像URLテスト</h2>
    <pre><?php
// テスト用の画像パス
$testPaths = [
    '/works/test.jpg',
    '/uploads/works/test.jpg',
    'works/test.jpg',
];

echo "各パターンのURL生成テスト:\n\n";
foreach ($testPaths as $path) {
    echo "入力: " . $path . "\n";
    echo "  site_url(): " . site_url($path) . "\n";

    // 修正ロジック適用
    $fixed = $path;
    if ($fixed && strpos($fixed, '/uploads/') === false && strpos($fixed, '/') === 0) {
        $fixed = '/uploads' . $fixed;
    }
    echo "  修正後: " . $fixed . "\n";
    echo "  最終URL: " . site_url($fixed) . "\n\n";
}
    ?></pre>

    <hr>
    <p><a href="/">トップページに戻る</a> | <a href="/admin/works">管理画面</a></p>
</body>
</html>
