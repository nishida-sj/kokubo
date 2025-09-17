<?php
// デバッグ用 index.php - サブドメイン環境用
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<!-- Debug: Starting index.php -->\n";

// 設定ファイル読み込み
$configPath = __DIR__ . '/config/config.php';
echo "<!-- Debug: Config path: $configPath -->\n";

if (!file_exists($configPath)) {
    die("エラー: 設定ファイルが見つかりません: $configPath");
}

try {
    require_once $configPath;
    echo "<!-- Debug: Config loaded successfully -->\n";
} catch (Exception $e) {
    die("エラー: 設定ファイルの読み込みに失敗しました: " . $e->getMessage());
}

try {
    // 必要なクラスが存在するかチェック
    $classesToCheck = ['Router', 'Controller', 'Database'];
    foreach ($classesToCheck as $class) {
        if (!class_exists($class)) {
            echo "<!-- Debug: Class $class not found, trying to load -->\n";
        }
    }

    // ルーター初期化
    echo "<!-- Debug: Initializing router -->\n";
    $router = new Router();
    echo "<!-- Debug: Router initialized -->\n";

    // ルート定義
    $router->define([
        '' => 'HomeController@index',
        'works' => 'WorksController@index',
        'works/{slug}' => 'WorksController@show',
        'contact' => 'ContactController@index',
        'contact/send' => 'ContactController@send',
        'sitemap.xml' => 'SitemapController@index',
        'admin' => 'Admin/LoginController@index',
        'admin/login' => 'Admin/LoginController@login',
        'admin/logout' => 'Admin/LoginController@logout',
        'admin/dashboard' => 'Admin/DashboardController@index',
        'admin/works' => 'Admin/WorksController@index',
        'admin/works/create' => 'Admin/WorksController@create',
        'admin/works/store' => 'Admin/WorksController@store',
        'admin/works/{id}/edit' => 'Admin/WorksController@edit',
        'admin/works/{id}/update' => 'Admin/WorksController@update',
        'admin/works/{id}/delete' => 'Admin/WorksController@delete',
        'admin/contacts' => 'Admin/ContactsController@index',
        'admin/settings' => 'Admin/SettingsController@index',
    ]);
    echo "<!-- Debug: Routes defined -->\n";

    // リクエストURI取得
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    echo "<!-- Debug: Request URI: $requestUri, Method: $requestMethod -->\n";

    // リクエスト処理
    echo "<!-- Debug: Starting route resolution -->\n";
    echo $router->resolve($requestUri, $requestMethod);
    echo "<!-- Debug: Route resolution completed -->\n";

} catch (Exception $e) {
    echo "<h1>エラーが発生しました</h1>";
    echo "<p><strong>メッセージ:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>ファイル:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>行:</strong> " . $e->getLine() . "</p>";
    echo "<h3>スタックトレース:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";

    // 現在のディレクトリとファイル存在確認
    echo "<h3>デバッグ情報:</h3>";
    echo "<p><strong>現在のディレクトリ:</strong> " . __DIR__ . "</p>";
    echo "<p><strong>APP_PATH:</strong> " . (defined('APP_PATH') ? APP_PATH : 'NOT DEFINED') . "</p>";

    if (defined('APP_PATH')) {
        echo "<p><strong>Router.php:</strong> " . (file_exists(APP_PATH . '/Router.php') ? 'EXISTS' : 'NOT FOUND') . "</p>";
        echo "<p><strong>Controller.php:</strong> " . (file_exists(APP_PATH . '/Controller.php') ? 'EXISTS' : 'NOT FOUND') . "</p>";
        echo "<p><strong>Database.php:</strong> " . (file_exists(APP_PATH . '/Database.php') ? 'EXISTS' : 'NOT FOUND') . "</p>";
    }
}
?>