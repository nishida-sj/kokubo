<?php
// 小久保工務店 フロントコントローラー

require_once '../config/config.php';

try {
    // ルーター初期化
    $router = new Router();

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

    // リクエスト処理
    echo $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "<h1>エラーが発生しました</h1>";
        echo "<pre>" . h($e->getMessage()) . "</pre>";
        echo "<pre>" . h($e->getTraceAsString()) . "</pre>";
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        echo "申し訳ございません。システムエラーが発生しました。";
    }
}