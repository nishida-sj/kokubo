<?php
// 小久保植樹園 メインindex.php - 完全版
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // 設定ファイル読み込み
    require_once __DIR__ . '/config/config.php';
    echo "<!-- Debug: Config loaded -->\n";

    // Router初期化
    $router = new Router();
    echo "<!-- Debug: Router created -->\n";

    // 完全なルート定義
    $router->define([
        '' => 'HomeController@index',
        'sub' => 'SubController@index',
        'works' => 'WorksController@index',
        'works/{slug}' => 'WorksController@show',
        'company' => 'CompanyController@index',
        'recruit' => 'RecruitController@index',
        'contact' => 'ContactController@index',
        'contact/send' => 'ContactController@send',
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
        'admin/works/{workId}/delete-image/{imageId}' => 'Admin/WorksController@deleteImage',
        'admin/contacts' => 'Admin/ContactsController@index',
        'admin/contacts/{id}/send-reply' => 'Admin/ContactsController@sendReply',
        'admin/contacts/{id}/reply' => 'Admin/ContactsController@reply',
        'admin/contacts/{id}/mark-read' => 'Admin/ContactsController@markAsRead',
        'admin/contacts/{id}/mark-unread' => 'Admin/ContactsController@markAsUnread',
        'admin/contacts/{id}/delete' => 'Admin/ContactsController@delete',
        'admin/contacts/{id}' => 'Admin/ContactsController@show',
        'admin/settings' => 'Admin/SettingsController@index',
        'admin/settings/update' => 'Admin/SettingsController@update',
        'admin/recruit' => 'Admin/RecruitController@index',
        'admin/recruit/update' => 'Admin/RecruitController@update',
        'admin/company' => 'Admin/CompanyController@index',
        'admin/company/update' => 'Admin/CompanyController@update',
    ]);
    echo "<!-- Debug: Routes defined -->\n";

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

    // デバッグ情報
    echo "<h3>デバッグ情報:</h3>";
    echo "<p><strong>現在のディレクトリ:</strong> " . __DIR__ . "</p>";
    echo "<p><strong>APP_PATH:</strong> " . (defined('APP_PATH') ? APP_PATH : 'NOT DEFINED') . "</p>";

    // クラスファイル存在確認
    if (defined('APP_PATH')) {
        $controllers = ['HomeController', 'WorksController', 'ContactController'];
        foreach ($controllers as $controller) {
            $file = APP_PATH . '/Controllers/' . $controller . '.php';
            $exists = file_exists($file) ? '✓' : '✗';
            echo "<p><strong>$controller:</strong> $exists ($file)</p>";
        }
    }

    echo "<h3>スタックトレース:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";

    echo '<p><a href="/">ホームに戻る</a></p>';
}
?>