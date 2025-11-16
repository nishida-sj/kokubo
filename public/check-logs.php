<?php
// エラーログ確認ページ
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>エラーログ確認</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #f5f5f5;
            line-height: 1.8;
        }
        .container {
            max-width: 1200px;
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
            font-size: 0.9em;
            max-height: 500px;
            overflow-y: auto;
        }
        .error-line {
            background: #ffebee;
            padding: 5px;
            margin: 2px 0;
            border-left: 3px solid #f44336;
        }
        .image-line {
            background: #e8f5e9;
            padding: 5px;
            margin: 2px 0;
            border-left: 3px solid #4CAF50;
        }
        .warning {
            background: #fff3e0;
            padding: 15px;
            border-left: 4px solid #FF9800;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 エラーログ確認</h1>

        <h2>1. PHPエラーログの場所</h2>
        <pre><?php
$errorLog = ini_get('error_log');
echo "error_log設定: " . ($errorLog ?: 'default') . "\n";

// 一般的なエラーログの場所を確認
$possibleLogs = [
    $errorLog,
    __DIR__ . '/error_log',
    __DIR__ . '/../error_log',
    __DIR__ . '/../storage/logs/error.log',
    '/var/log/php-error.log',
    '/var/log/apache2/error.log',
];

echo "\n確認中のログファイル:\n";
foreach ($possibleLogs as $log) {
    if ($log && file_exists($log)) {
        echo "✅ 存在: " . $log . " (" . number_format(filesize($log)) . " bytes)\n";
    } else if ($log) {
        echo "❌ なし: " . $log . "\n";
    }
}
        ?></pre>

        <h2>2. 最新のPHPエラー（error_log）</h2>
        <?php
        $errorLog = ini_get('error_log');
        if (!$errorLog) {
            // デフォルトの場所を試す
            $errorLog = __DIR__ . '/error_log';
        }

        if (file_exists($errorLog) && is_readable($errorLog)) {
            echo '<p>ファイル: <code>' . htmlspecialchars($errorLog) . '</code> (' . number_format(filesize($errorLog)) . ' bytes)</p>';

            // 最新100行を取得
            $lines = @file($errorLog);
            if ($lines) {
                $lines = array_slice($lines, -200); // 最新200行
                echo '<pre>';
                foreach ($lines as $line) {
                    $line = htmlspecialchars($line);
                    if (stripos($line, 'error') !== false || stripos($line, 'fatal') !== false) {
                        echo '<div class="error-line">' . $line . '</div>';
                    } else if (stripos($line, 'ImageTool') !== false || stripos($line, 'upload') !== false) {
                        echo '<div class="image-line">' . $line . '</div>';
                    } else {
                        echo $line;
                    }
                }
                echo '</pre>';
            } else {
                echo '<p class="warning">⚠️ ログファイルを読み取れませんでした。</p>';
            }
        } else {
            echo '<p class="warning">⚠️ エラーログファイルが見つかりません。</p>';
        }
        ?>

        <h2>3. アプリケーションログ（storage/logs）</h2>
        <?php
        $appLogDir = __DIR__ . '/storage/logs';
        if (!is_dir($appLogDir)) {
            $appLogDir = __DIR__ . '/../storage/logs';
        }

        if (is_dir($appLogDir)) {
            $logFiles = glob($appLogDir . '/*.log');
            if ($logFiles) {
                foreach ($logFiles as $logFile) {
                    echo '<h3>' . basename($logFile) . ' (' . number_format(filesize($logFile)) . ' bytes)</h3>';
                    $lines = @file($logFile);
                    if ($lines) {
                        $lines = array_slice($lines, -100); // 最新100行
                        echo '<pre>';
                        foreach ($lines as $line) {
                            $line = htmlspecialchars($line);
                            if (stripos($line, 'error') !== false) {
                                echo '<div class="error-line">' . $line . '</div>';
                            } else if (stripos($line, 'ImageTool') !== false) {
                                echo '<div class="image-line">' . $line . '</div>';
                            } else {
                                echo $line;
                            }
                        }
                        echo '</pre>';
                    }
                }
            } else {
                echo '<p>ログファイルがありません。</p>';
            }
        } else {
            echo '<p class="warning">⚠️ storage/logsディレクトリが見つかりません。</p>';
        }
        ?>

        <h2>4. データベースの最新実績データ</h2>
        <?php
        // config読み込み試行
        $configPath = __DIR__ . '/config/config.php';
        if (!file_exists($configPath)) {
            $configPath = __DIR__ . '/../config/config.php';
        }

        if (file_exists($configPath)) {
            require_once $configPath;

            try {
                $db = Db::getInstance();
                $works = $db->fetchAll("SELECT id, title, main_image, created_at FROM works ORDER BY id DESC LIMIT 5");

                echo '<table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">';
                echo '<tr style="background: #2E7D32; color: white;">
                        <th>ID</th>
                        <th>タイトル</th>
                        <th>main_image</th>
                        <th>ファイル存在</th>
                        <th>登録日時</th>
                      </tr>';

                foreach ($works as $work) {
                    $imagePath = $work['main_image'];
                    $fullPath = __DIR__ . $imagePath;
                    $fileExists = file_exists($fullPath);

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($work['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($work['title']) . '</td>';
                    echo '<td><code>' . htmlspecialchars($imagePath) . '</code></td>';
                    echo '<td>' . ($fileExists ? '✅ 存在' : '❌ なし') . '</td>';
                    echo '<td>' . htmlspecialchars($work['created_at']) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } catch (Exception $e) {
                echo '<p class="warning">⚠️ データベースエラー: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<p class="warning">⚠️ 設定ファイルが読み込めないため、データベース確認をスキップしました。</p>';
        }
        ?>

        <div style="margin-top: 40px; padding: 20px; background: #f0f0f0; border-radius: 8px;">
            <h3>📝 次のステップ</h3>
            <ol>
                <li>このページを確認して、ImageTool関連のエラーがないか探す</li>
                <li>管理画面から画像をアップロードしてみる</li>
                <li>このページをリロードして、新しいエラーログを確認</li>
            </ol>

            <div style="text-align: center; margin-top: 20px;">
                <a href="/check-permissions.php" style="margin: 0 10px;">← 権限チェック</a> |
                <a href="/admin/works/create" style="margin: 0 10px;">実績登録</a> |
                <a href="javascript:location.reload();" style="margin: 0 10px;">🔄 ログ再読込</a>
            </div>
        </div>
    </div>
</body>
</html>
