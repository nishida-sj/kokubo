<?php

class HomeController extends Controller
{
    public function index()
    {
        try {
            // 基本的なHTML出力でテスト
            $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>小久保植樹園 | 伊勢市の植樹・造園専門業者</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 40px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; text-align: center; margin-bottom: 30px; }
        .section { margin: 30px 0; padding: 20px; background: #f5f5f5; border-radius: 4px; }
        .works-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .work-card { background: white; padding: 20px; border-radius: 4px; border: 1px solid #ddd; }
        .nav { text-align: center; margin: 20px 0; }
        .nav a { margin: 0 15px; color: #2E7D32; text-decoration: none; font-weight: 500; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌿 小久保植樹園</h1>
        <p style="text-align: center; font-size: 18px; color: #666;">伊勢市の植樹・造園専門業者</p>

        <div class="nav">
            <a href="/">ホーム</a>
            <a href="/works">施工実績</a>
            <a href="/contact">お問い合わせ</a>
            <a href="/admin">管理画面</a>
        </div>

        <div class="section">
            <h2>🏡 サービス内容</h2>
            <div class="works-grid">
                <div class="work-card">
                    <h3>庭園設計</h3>
                    <p>お客様のご要望に合わせた庭園の設計・施工を行います。</p>
                </div>
                <div class="work-card">
                    <h3>植栽工事</h3>
                    <p>住宅やマンション、公共施設の植栽工事を承ります。</p>
                </div>
                <div class="work-card">
                    <h3>樹木管理</h3>
                    <p>定期的な剪定・管理で美しい緑を維持します。</p>
                </div>
                <div class="work-card">
                    <h3>剪定作業</h3>
                    <p>樹種に応じた適切な剪定で健康な樹木を育てます。</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📞 お問い合わせ</h2>
            <p><strong>電話:</strong> 0596-00-0000</p>
            <p><strong>メール:</strong> info@kokubosyokuju.geo.jp</p>
            <p><strong>営業時間:</strong> 平日 8:00-18:00 / 土曜 8:00-17:00</p>
        </div>

        <div class="section">
            <h2>✅ システム動作確認</h2>
            <p>✓ PHP ' . phpversion() . ' で動作</p>
            <p>✓ データベース接続: ' . (defined('DB_NAME') ? DB_NAME : 'Not configured') . '</p>
            <p>✓ 現在時刻: ' . date('Y年m月d日 H:i:s') . '</p>
        </div>
    </div>
</body>
</html>';

            return $html;

        } catch (Exception $e) {
            return '<h1>HomeController Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}