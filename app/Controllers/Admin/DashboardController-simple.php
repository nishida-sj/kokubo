<?php

namespace Admin;

class DashboardController extends \Controller
{
    public function index()
    {
        // ログインチェック
        if (!is_admin_logged_in()) {
            header('Location: /admin');
            exit;
        }

        try {
            $db = \Database::getInstance();

            // 統計情報を取得
            $worksCount = $db->fetch("SELECT COUNT(*) as count FROM works")['count'] ?? 0;
            $categoriesCount = $db->fetch("SELECT COUNT(*) as count FROM categories")['count'] ?? 0;
            $contactsCount = $db->fetch("SELECT COUNT(*) as count FROM contacts")['count'] ?? 0;

            $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面ダッシュボード | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; margin: 0; }
        .user-info { float: right; color: #666; }
        .user-info a { color: #2E7D32; text-decoration: none; margin-left: 10px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-card { background: white; padding: 30px; border-radius: 8px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-number { font-size: 36px; font-weight: bold; color: #2E7D32; }
        .stat-label { color: #666; margin-top: 10px; }
        .menu { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
        .menu-item { padding: 20px; background: #f5f5f5; border-radius: 4px; text-align: center; text-decoration: none; color: #333; }
        .menu-item:hover { background: #e8f5e8; color: #2E7D32; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header clearfix">
            <h1>🌿 管理画面ダッシュボード</h1>
            <div class="user-info">
                ようこそ、' . h($_SESSION['admin_username'] ?? 'admin') . 'さん
                <a href="/admin/logout">ログアウト</a>
                <a href="/">サイト表示</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">' . $worksCount . '</div>
                <div class="stat-label">📋 施工実績</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">' . $categoriesCount . '</div>
                <div class="stat-label">📁 カテゴリー</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">' . $contactsCount . '</div>
                <div class="stat-label">📞 お問い合わせ</div>
            </div>
        </div>

        <div class="menu">
            <h2>管理メニュー</h2>
            <div class="menu-grid">
                <a href="/admin/works" class="menu-item">
                    📋 実績管理<br>
                    <small>施工実績の追加・編集</small>
                </a>
                <a href="/admin/contacts" class="menu-item">
                    📞 お問い合わせ管理<br>
                    <small>お問い合わせ一覧・返信</small>
                </a>
                <a href="/admin/settings" class="menu-item">
                    ⚙️ サイト設定<br>
                    <small>基本情報・SEO設定</small>
                </a>
                <a href="/" class="menu-item">
                    🌿 サイト表示<br>
                    <small>公開サイトを確認</small>
                </a>
            </div>
        </div>
    </div>
</body>
</html>';

            return $html;

        } catch (Exception $e) {
            return '<h1>Dashboard Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}