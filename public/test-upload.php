<?php
// 画像アップロードテストページ
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/config.php';

// 管理者チェック
if (!is_admin_logged_in()) {
    die('このページにアクセスするには管理者ログインが必要です。<br><a href="/admin">ログインページへ</a>');
}

$message = '';
$uploadResult = null;

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    try {
        $uploadResult = ImageTool::upload('test_image', 'works', true);
        $message = '<div style="background: #e8f5e9; padding: 20px; border-left: 4px solid #4CAF50; margin: 20px 0;">';
        $message .= '<h3 style="color: #2E7D32; margin-top: 0;">✅ アップロード成功！</h3>';
        $message .= '<pre>' . print_r($uploadResult, true) . '</pre>';

        // 実際にファイルが存在するか確認
        $filePath = BASE_PATH . $uploadResult['path'];
        if (file_exists($filePath)) {
            $message .= '<p style="color: green;">✅ ファイルが存在します: ' . htmlspecialchars($filePath) . '</p>';
            $message .= '<p>ファイルサイズ: ' . number_format(filesize($filePath)) . ' bytes</p>';
            $message .= '<img src="' . htmlspecialchars(site_url($uploadResult['path'])) . '" style="max-width: 400px; border: 1px solid #ddd; margin-top: 10px;">';
        } else {
            $message .= '<p style="color: red;">❌ ファイルが存在しません: ' . htmlspecialchars($filePath) . '</p>';
        }

        $message .= '</div>';
    } catch (Exception $e) {
        $message = '<div style="background: #ffebee; padding: 20px; border-left: 4px solid #f44336; margin: 20px 0;">';
        $message .= '<h3 style="color: #c62828; margin-top: 0;">❌ アップロード失敗</h3>';
        $message .= '<p><strong>エラーメッセージ:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        $message .= '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        $message .= '</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロードテスト | 小久保植樹園</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .section h2 {
            color: #2E7D32;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        .info-box {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            border-left: 4px solid #2196F3;
        }
        .info-box pre {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 0.9em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
        }
        .btn {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .status-ok { color: #4CAF50; font-weight: bold; }
        .status-error { color: #f44336; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 画像アップロードテスト</h1>
            <p>アップロード機能と権限を確認します</p>
        </div>

        <div class="content">
            <?php if ($message): ?>
                <?= $message ?>
            <?php endif; ?>

            <!-- システム情報 -->
            <div class="section">
                <h2>📊 システム情報</h2>

                <div class="info-box">
                    <h3>PHP設定</h3>
                    <pre><?php
echo "PHP Version: " . PHP_VERSION . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
                    ?></pre>
                </div>

                <div class="info-box">
                    <h3>パス設定</h3>
                    <pre><?php
echo "BASE_PATH: " . BASE_PATH . "\n";
echo "UPLOAD_PATH: " . UPLOAD_PATH . "\n";
echo "UPLOAD_URL: " . UPLOAD_URL . "\n";
                    ?></pre>
                </div>

                <div class="info-box">
                    <h3>ディレクトリ権限</h3>
                    <pre><?php
$uploadsDir = UPLOAD_PATH;
$worksDir = UPLOAD_PATH . '/works';
$thumbsDir = UPLOAD_PATH . '/works/thumbs';

echo "uploads/ 存在: " . (is_dir($uploadsDir) ? "✅" : "❌") . "\n";
echo "uploads/ 書き込み可能: " . (is_writable($uploadsDir) ? "✅" : "❌") . "\n";
if (is_dir($uploadsDir)) {
    echo "uploads/ パーミッション: " . substr(sprintf('%o', fileperms($uploadsDir)), -4) . "\n";
}

echo "\n";

echo "works/ 存在: " . (is_dir($worksDir) ? "✅" : "❌") . "\n";
echo "works/ 書き込み可能: " . (is_writable($worksDir) ? "✅" : "❌") . "\n";
if (is_dir($worksDir)) {
    echo "works/ パーミッション: " . substr(sprintf('%o', fileperms($worksDir)), -4) . "\n";
}

echo "\n";

echo "thumbs/ 存在: " . (is_dir($thumbsDir) ? "✅" : "❌") . "\n";
echo "thumbs/ 書き込み可能: " . (is_writable($thumbsDir) ? "✅" : "❌") . "\n";
if (is_dir($thumbsDir)) {
    echo "thumbs/ パーミッション: " . substr(sprintf('%o', fileperms($thumbsDir)), -4) . "\n";
}
                    ?></pre>
                </div>

                <div class="info-box">
                    <h3>GDライブラリ</h3>
                    <pre><?php
if (function_exists('gd_info')) {
    $gd = gd_info();
    echo "GD Version: " . $gd['GD Version'] . "\n";
    echo "JPEG Support: " . ($gd['JPEG Support'] ? "✅" : "❌") . "\n";
    echo "PNG Support: " . ($gd['PNG Support'] ? "✅" : "❌") . "\n";
    echo "GIF Support: " . ($gd['GIF Create Support'] ? "✅" : "❌") . "\n";
} else {
    echo "❌ GDライブラリが利用できません\n";
}
                    ?></pre>
                </div>
            </div>

            <!-- アップロードテストフォーム -->
            <div class="section">
                <h2>📤 アップロードテスト</h2>
                <p style="margin-bottom: 20px;">画像をアップロードして、実際に保存されるかテストします。</p>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="test_image" class="form-label">テスト画像を選択</label>
                        <input type="file"
                               id="test_image"
                               name="test_image"
                               accept="image/*"
                               class="form-input"
                               required>
                        <small style="color: #666; display: block; margin-top: 5px;">
                            JPG, PNG, GIF形式の画像を選択してください（最大5MB）
                        </small>
                    </div>

                    <button type="submit" class="btn">🚀 アップロードテスト実行</button>
                </form>
            </div>

            <!-- 既存ファイル一覧 -->
            <div class="section">
                <h2>📁 既存ファイル一覧</h2>

                <?php
                $worksDir = UPLOAD_PATH . '/works';
                if (is_dir($worksDir)) {
                    $files = array_diff(scandir($worksDir), ['.', '..', '.gitkeep']);

                    if (empty($files)) {
                        echo '<p style="color: #999;">アップロードされた画像はまだありません。</p>';
                    } else {
                        echo '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">';
                        foreach ($files as $file) {
                            $filePath = $worksDir . '/' . $file;
                            if (is_file($filePath)) {
                                $fileSize = filesize($filePath);
                                $fileUrl = site_url('/uploads/works/' . $file);

                                echo '<div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">';
                                echo '<img src="' . htmlspecialchars($fileUrl) . '" style="width: 100%; height: 150px; object-fit: cover;">';
                                echo '<div style="padding: 10px; background: #f9f9f9;">';
                                echo '<div style="font-size: 0.8em; color: #666; word-break: break-all;">' . htmlspecialchars($file) . '</div>';
                                echo '<div style="font-size: 0.8em; color: #999; margin-top: 5px;">' . number_format($fileSize) . ' bytes</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p class="status-error">❌ worksディレクトリが存在しません</p>';
                }
                ?>
            </div>

            <div style="text-align: center; padding: 20px; color: #666;">
                <a href="/debug-images.php">← デバッグページに戻る</a> |
                <a href="/admin/works">管理画面へ</a>
            </div>
        </div>
    </div>
</body>
</html>
