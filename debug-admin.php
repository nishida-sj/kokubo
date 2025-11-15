<?php
// 管理画面デバッグ用ページ
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>管理画面デバッグ情報</h1>";
echo "<hr>";

// 基本パス情報
echo "<h2>パス情報</h2>";
echo "<pre>";
echo "現在のディレクトリ: " . getcwd() . "\n";
echo "__DIR__: " . __DIR__ . "\n";
echo "APP_PATH定義済み: " . (defined('APP_PATH') ? 'Yes' : 'No') . "\n";
echo "</pre>";

// 設定ファイル読み込み
require_once __DIR__ . '/config/config.php';

echo "<h2>定数確認</h2>";
echo "<pre>";
echo "APP_PATH: " . APP_PATH . "\n";
echo "DEBUG_MODE: " . (DEBUG_MODE ? 'true' : 'false') . "\n";
echo "</pre>";

// Adminディレクトリの確認
echo "<h2>Adminディレクトリの確認</h2>";
$adminPath = APP_PATH . '/Controllers/Admin';
echo "<pre>";
echo "Admin Path: " . $adminPath . "\n";
echo "存在する: " . (file_exists($adminPath) ? 'Yes' : 'No') . "\n";

if (file_exists($adminPath)) {
    echo "\nAdmin Controllers:\n";
    $files = scandir($adminPath);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - " . $file . "\n";
        }
    }
}
echo "</pre>";

// WorksControllerの確認
echo "<h2>WorksController確認</h2>";
$worksControllerPath = APP_PATH . '/Controllers/Admin/WorksController.php';
echo "<pre>";
echo "Path: " . $worksControllerPath . "\n";
echo "存在する: " . (file_exists($worksControllerPath) ? 'Yes' : 'No') . "\n";

if (file_exists($worksControllerPath)) {
    echo "ファイルサイズ: " . filesize($worksControllerPath) . " bytes\n";
    echo "読み込み可能: " . (is_readable($worksControllerPath) ? 'Yes' : 'No') . "\n";
}
echo "</pre>";

// クラス読み込みテスト
echo "<h2>クラス読み込みテスト</h2>";
echo "<pre>";

if (file_exists($worksControllerPath)) {
    try {
        require_once $worksControllerPath;
        echo "✅ ファイル読み込み成功\n";

        // クラスの存在確認
        $possibleClasses = [
            'Admin_WorksController',
            'Admin\\WorksController',
            'WorksController'
        ];

        foreach ($possibleClasses as $className) {
            if (class_exists($className, false)) {
                echo "✅ クラス発見: " . $className . "\n";

                // メソッド一覧
                $methods = get_class_methods($className);
                echo "  メソッド: " . implode(', ', $methods) . "\n";
                break;
            }
        }

        // クラスが見つからない場合
        $declaredClasses = get_declared_classes();
        $adminClasses = array_filter($declaredClasses, function($class) {
            return strpos($class, 'Admin') !== false || strpos($class, 'Works') !== false;
        });

        if (!empty($adminClasses)) {
            echo "\n見つかったAdmin関連クラス:\n";
            foreach ($adminClasses as $class) {
                echo "  - " . $class . "\n";
            }
        }

    } catch (Exception $e) {
        echo "❌ エラー: " . $e->getMessage() . "\n";
        echo "スタックトレース:\n" . $e->getTraceAsString() . "\n";
    }
} else {
    echo "❌ ファイルが存在しません\n";
}

echo "</pre>";

// ルーターテスト
echo "<h2>ルーターテスト</h2>";
echo "<pre>";
try {
    $router = new Router();
    echo "✅ Routerクラス読み込み成功\n";

    // ルート定義
    $router->define([
        'admin/works' => 'Admin/WorksController@index',
    ]);

    echo "✅ ルート定義成功\n";

    // テスト実行
    echo "\nルート解決テスト: admin/works\n";
    $result = $router->resolve('admin/works', 'GET');
    echo "結果: " . (is_string($result) ? substr($result, 0, 100) . '...' : 'OK') . "\n";

} catch (Exception $e) {
    echo "❌ ルーターエラー: " . $e->getMessage() . "\n";
    echo "\nファイル: " . $e->getFile() . "\n";
    echo "行: " . $e->getLine() . "\n";
    echo "\nスタックトレース:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";

echo "<hr>";
echo "<p><a href='/'>トップページに戻る</a></p>";
