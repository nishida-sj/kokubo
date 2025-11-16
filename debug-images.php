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
echo "=== パス設定確認 ===\n";
echo "BASE_PATH: " . BASE_PATH . "\n";
echo "PUBLIC_PATH: " . PUBLIC_PATH . "\n";
echo "UPLOAD_PATH: " . UPLOAD_PATH . "\n\n";

$uploadsDir = UPLOAD_PATH;
echo "Uploadsディレクトリパス: " . $uploadsDir . "\n";
echo "存在: " . (is_dir($uploadsDir) ? "✅ Yes" : "❌ No") . "\n";

if (!is_dir($uploadsDir)) {
    // 他のパターンも試す
    echo "\n❌ uploadsディレクトリが見つかりません。他のパスを確認中...\n";
    $alternatives = [
        __DIR__ . '/uploads',
        PUBLIC_PATH . '/uploads',
        BASE_PATH . '/public/uploads',
        '/virtual/nishidasj/public_html/kokubosyokuju.geo.jp/uploads'
    ];
    foreach ($alternatives as $altPath) {
        echo "  試行: " . $altPath . " - " . (is_dir($altPath) ? "✅ 存在" : "❌ なし") . "\n";
    }
}

if (is_dir($uploadsDir)) {
    echo "\n✅ サブディレクトリ:\n";
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

                // ファイルリストも表示
                if ($fileCount > 0 && $fileCount < 20) {
                    foreach ($files as $file) {
                        if ($file !== '.' && $file !== '..' && is_file($path . '/' . $file)) {
                            $size = filesize($path . '/' . $file);
                            echo "      • " . $file . " (" . number_format($size) . " bytes)\n";
                        }
                    }
                }
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
$worksDir = UPLOAD_PATH . '/works';
echo "チェック中のパス: " . $worksDir . "\n\n";

if (is_dir($worksDir)) {
    echo "✅ /uploads/works ディレクトリが存在します\n\n";
    echo "ファイル一覧:\n";
    $files = scandir($worksDir);
    $imageFiles = [];
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && is_file($worksDir . '/' . $file)) {
            $size = filesize($worksDir . '/' . $file);
            echo "  - " . $file . " (" . number_format($size) . " bytes)\n";
            $imageFiles[] = $file;
        }
    }

    if (empty($imageFiles)) {
        echo "\n⚠️ 画像ファイルがありません。管理画面から画像をアップロードしてください。\n";
    }

    // サムネイルディレクトリも確認
    $thumbsDir = $worksDir . '/thumbs';
    echo "\n";
    if (is_dir($thumbsDir)) {
        echo "✅ /uploads/works/thumbs ディレクトリが存在します\n";
        $thumbFiles = scandir($thumbsDir);
        $thumbCount = 0;
        foreach ($thumbFiles as $file) {
            if ($file !== '.' && $file !== '..' && is_file($thumbsDir . '/' . $file)) {
                $thumbCount++;
            }
        }
        echo "サムネイル数: " . $thumbCount . "\n";
    } else {
        echo "❌ /uploads/works/thumbs ディレクトリが存在しません\n";
    }
} else {
    echo "❌ /uploads/works ディレクトリが存在しません\n";
    echo "パス: " . $worksDir . "\n";

    // 代替パスを確認
    echo "\n他のパターンを確認:\n";
    $altPaths = [
        __DIR__ . '/uploads/works',
        BASE_PATH . '/public/uploads/works',
    ];
    foreach ($altPaths as $altPath) {
        echo "  - " . $altPath . ": " . (is_dir($altPath) ? "✅ 存在" : "❌ なし") . "\n";
    }
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
