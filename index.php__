<?php
// 小久保植樹園 メインindex.php - 修正版
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!-- Debug: Starting index.php -->\n";

try {
    // 設定ファイル読み込み
    $configPath = __DIR__ . '/config/config.php';
    if (!file_exists($configPath)) {
        throw new Exception("Config file not found: $configPath");
    }

    require_once $configPath;
    echo "<!-- Debug: Config loaded -->\n";

    // 基本クラスを明示的に読み込み
    $classes = ['Router', 'Controller', 'Database'];
    foreach ($classes as $class) {
        $classFile = __DIR__ . "/app/$class.php";
        if (file_exists($classFile)) {
            require_once $classFile;
            echo "<!-- Debug: $class loaded -->\n";
        } else {
            throw new Exception("Class file not found: $classFile");
        }
    }

    // Routerインスタンス作成
    $router = new Router();
    echo "<!-- Debug: Router created -->\n";

    // 簡単なルート定義
    $router->define([
        '' => 'TestController@index',
        'test' => 'TestController@test'
    ]);
    echo "<!-- Debug: Routes defined -->\n";

    // テスト用コントローラー
    class TestController {
        public function index() {
            return '<h1>小久保植樹園</h1><p>サイトが正常に動作しています！</p><p><a href="/test">テストページ</a></p>';
        }

        public function test() {
            return '<h1>テストページ</h1><p>ルーティングが正常に動作しています！</p><p><a href="/">トップに戻る</a></p>';
        }
    }

    // リクエスト処理
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    echo "<!-- Debug: Processing URI: $uri -->\n";

    echo $router->resolve($uri, $method);
    echo "<!-- Debug: Request completed -->\n";

} catch (Exception $e) {
    echo "<h1>エラーが発生しました</h1>";
    echo "<p><strong>エラー:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>ファイル:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>行:</strong> " . $e->getLine() . "</p>";
    echo "<h3>スタックトレース:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>