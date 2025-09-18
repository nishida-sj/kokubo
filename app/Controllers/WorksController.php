<?php

class WorksController extends Controller
{
    public function index()
    {
        try {
            // データベースから実績を取得
            $db = Database::getInstance();
            $works = $db->fetchAll("
                SELECT w.*, c.name as category_name
                FROM works w
                LEFT JOIN categories c ON w.category_id = c.id
                WHERE w.is_published = 1
                ORDER BY w.created_at DESC
            ");

            $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>施工実績 | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 40px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; text-align: center; margin-bottom: 30px; }
        .nav { text-align: center; margin: 20px 0; }
        .nav a { margin: 0 15px; color: #2E7D32; text-decoration: none; font-weight: 500; }
        .nav a:hover { text-decoration: underline; }
        .works-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; margin-top: 30px; }
        .work-card { background: #f9f9f9; padding: 25px; border-radius: 8px; border: 1px solid #ddd; }
        .work-title { color: #2E7D32; font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .work-category { color: #666; font-size: 14px; margin-bottom: 15px; }
        .work-description { line-height: 1.6; color: #444; }
        .work-location { color: #888; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌿 施工実績</h1>

        <div class="nav">
            <a href="/">ホーム</a>
            <a href="/works">施工実績</a>
            <a href="/contact">お問い合わせ</a>
            <a href="/admin">管理画面</a>
        </div>';

            if (empty($works)) {
                $html .= '<p style="text-align: center; color: #666; margin: 40px 0;">実績データがありません。管理画面から追加してください。</p>';
            } else {
                $html .= '<div class="works-grid">';
                foreach ($works as $work) {
                    $html .= '
                    <div class="work-card">
                        <div class="work-title">' . h($work['title']) . '</div>
                        <div class="work-category">📋 ' . h($work['category_name']) . '</div>
                        <div class="work-description">' . h($work['description']) . '</div>';

                    if ($work['location']) {
                        $html .= '<div class="work-location">📍 ' . h($work['location']) . '</div>';
                    }

                    $html .= '</div>';
                }
                $html .= '</div>';
            }

            $html .= '
        <div style="margin-top: 40px; padding: 20px; background: #f5f5f5; border-radius: 4px;">
            <h3>📊 実績統計</h3>
            <p>✓ 登録実績数: ' . count($works) . '件</p>
            <p>✓ データベース: ' . (defined('DB_NAME') ? DB_NAME : 'Not configured') . '</p>
        </div>
    </div>
</body>
</html>';

            return $html;

        } catch (Exception $e) {
            return '<h1>Works Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p><p><a href="/">ホームに戻る</a></p>';
        }
    }

    public function show($slug)
    {
        return '<h1>実績詳細: ' . htmlspecialchars($slug) . '</h1><p><a href="/works">実績一覧に戻る</a></p>';
    }
}