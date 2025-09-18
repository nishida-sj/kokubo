<?php

class HomeController extends Controller
{
    public function index()
    {
        try {
            // データベースから実績を取得
            $db = Database::getInstance();

            // おすすめ実績を取得
            $featuredWorks = $db->fetchAll("
                SELECT w.*, c.name as category_name
                FROM works w
                LEFT JOIN categories c ON w.category_id = c.id
                WHERE w.is_published = 1
                ORDER BY w.created_at DESC
                LIMIT 6
            ");

            $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>小久保植樹園 | 伊勢市の植樹・造園専門業者</title>
    <meta name="description" content="伊勢市の植樹園。植栽工事・庭園設計・樹木管理を手がける地域密着の造園業者です。緑豊かな空間づくりをお手伝いします。">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Hiragino Sans", "ヒラギノ角ゴ Pro", "Meiryo", sans-serif; line-height: 1.6; color: #333; }

        /* ヘッダー */
        .header { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: fixed; width: 100%; top: 0; z-index: 1000; }
        .header-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #2E7D32; text-decoration: none; }
        .nav { display: flex; gap: 30px; }
        .nav a { text-decoration: none; color: #333; font-weight: 500; transition: color 0.3s; }
        .nav a:hover { color: #2E7D32; }

        /* メインビジュアル */
        .hero { height: 80vh; background: linear-gradient(rgba(46, 125, 50, 0.7), rgba(46, 125, 50, 0.7)), url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1200 800\'%3E%3Crect fill=\'%234CAF50\' width=\'1200\' height=\'800\'/%3E%3Cpath fill=\'%2366BB6A\' d=\'M0 400c100-50 200-50 300 0s200 50 300 0 200-50 300 0 200 50 300 0v400H0V400z\'/%3E%3C/svg%3E"); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; color: white; text-align: center; margin-top: 80px; }
        .hero-content h1 { font-size: 48px; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .hero-content p { font-size: 20px; margin-bottom: 30px; }
        .hero-btn { background: #FFF; color: #2E7D32; padding: 15px 40px; text-decoration: none; border-radius: 50px; font-weight: bold; transition: transform 0.3s; display: inline-block; }
        .hero-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        /* コンセプトセクション */
        .concept { padding: 80px 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-title { text-align: center; font-size: 36px; color: #2E7D32; margin-bottom: 20px; }
        .section-subtitle { text-align: center; color: #666; margin-bottom: 50px; font-size: 18px; }
        .concept-content { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .concept-text { font-size: 16px; line-height: 1.8; }
        .concept-text h3 { color: #2E7D32; margin-bottom: 20px; font-size: 24px; }
        .concept-image { text-align: center; padding: 40px; background: #E8F5E8; border-radius: 10px; }
        .concept-image .icon { font-size: 80px; color: #2E7D32; }

        /* サービスセクション */
        .services { padding: 80px 20px; }
        .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 50px; }
        .service-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; }
        .service-card:hover { transform: translateY(-5px); }
        .service-icon { font-size: 60px; color: #2E7D32; margin-bottom: 20px; }
        .service-card h3 { color: #2E7D32; margin-bottom: 15px; font-size: 20px; }
        .service-card p { color: #666; line-height: 1.6; }

        /* 実績セクション */
        .works { padding: 80px 20px; background: #f8f9fa; }
        .works-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; margin-top: 50px; }
        .work-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .work-card:hover { transform: translateY(-5px); }
        .work-image { height: 200px; background: linear-gradient(45deg, #4CAF50, #81C784); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; }
        .work-content { padding: 25px; }
        .work-content h3 { color: #2E7D32; margin-bottom: 10px; }
        .work-content .category { color: #666; font-size: 14px; margin-bottom: 10px; }
        .work-content p { color: #666; line-height: 1.6; }

        /* お問い合わせセクション */
        .contact-section { padding: 80px 20px; background: #2E7D32; color: white; text-align: center; }
        .contact-content { max-width: 800px; margin: 0 auto; }
        .contact-section h2 { font-size: 36px; margin-bottom: 20px; }
        .contact-section p { font-size: 18px; margin-bottom: 40px; }
        .contact-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin: 40px 0; }
        .contact-item { background: rgba(255,255,255,0.1); padding: 30px; border-radius: 10px; }
        .contact-item h3 { margin-bottom: 15px; }
        .contact-btn { background: #FFF; color: #2E7D32; padding: 15px 40px; text-decoration: none; border-radius: 50px; font-weight: bold; transition: transform 0.3s; display: inline-block; margin: 10px; }
        .contact-btn:hover { transform: translateY(-2px); }

        /* フッター */
        .footer { background: #1B5E20; color: white; padding: 40px 20px; text-align: center; }
        .footer-content { max-width: 1200px; margin: 0 auto; }
        .footer p { margin: 5px 0; }

        /* レスポンシブ */
        @media (max-width: 768px) {
            .hero-content h1 { font-size: 32px; }
            .hero-content p { font-size: 16px; }
            .concept-content { grid-template-columns: 1fr; }
            .nav { display: none; }
            .section-title { font-size: 28px; }
        }
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo">🌿 小久保植樹園</a>
            <nav class="nav">
                <a href="/">ホーム</a>
                <a href="/works">施工実績</a>
                <a href="/contact">お問い合わせ</a>
                <a href="/admin">管理画面</a>
            </nav>
        </div>
    </header>

    <!-- メインビジュアル -->
    <section class="hero">
        <div class="hero-content">
            <h1>緑豊かな空間づくり</h1>
            <p>伊勢市の植樹・造園専門業者として、お客様の想いを形にします</p>
            <a href="/contact" class="hero-btn">お問い合わせ</a>
        </div>
    </section>

    <!-- コンセプト -->
    <section class="concept">
        <div class="container">
            <h2 class="section-title">私たちのコンセプト</h2>
            <p class="section-subtitle">四季を通じて美しい緑空間をお客様と共に育てます</p>
            <div class="concept-content">
                <div class="concept-text">
                    <h3>地域に根ざした造園業者として</h3>
                    <p>小久保植樹園は、伊勢市を中心とした地域密着の造園業者です。長年培った技術と経験を活かし、お客様一人ひとりのご要望に丁寧にお応えしています。</p>
                    <p>私たちは単に木を植えるだけでなく、その土地の特性を活かし、四季を通じて美しい景観を演出する空間づくりを心がけています。</p>
                </div>
                <div class="concept-image">
                    <div class="icon">🌳</div>
                    <h3>自然との調和</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- サービス -->
    <section class="services">
        <div class="container">
            <h2 class="section-title">サービス内容</h2>
            <p class="section-subtitle">植栽から管理まで、緑に関するあらゆるニーズにお応えします</p>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🏡</div>
                    <h3>庭園設計</h3>
                    <p>お客様のご要望に合わせた庭園の設計・施工を行います。和風・洋風問わず、美しい庭園をお作りします。</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🌱</div>
                    <h3>植栽工事</h3>
                    <p>住宅やマンション、公共施設の植栽工事を承ります。適切な植物選びから施工まで一貫して対応。</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🌿</div>
                    <h3>樹木管理</h3>
                    <p>定期的な剪定・管理で美しい緑を維持します。樹木の健康状態をチェックし、適切なケアを提供。</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">✂️</div>
                    <h3>剪定作業</h3>
                    <p>樹種に応じた適切な剪定で健康な樹木を育てます。美しい樹形づくりもお任せください。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 施工実績 -->
    <section class="works">
        <div class="container">
            <h2 class="section-title">施工実績</h2>
            <p class="section-subtitle">これまでの施工事例をご紹介します</p>
            <div class="works-grid">';

            if (!empty($featuredWorks)) {
                foreach ($featuredWorks as $work) {
                    $html .= '
                    <div class="work-card">
                        <div class="work-image">🌳 ' . h($work['category_name']) . '</div>
                        <div class="work-content">
                            <h3>' . h($work['title']) . '</h3>
                            <div class="category">📋 ' . h($work['category_name']) . '</div>
                            <p>' . h(mb_substr($work['description'], 0, 80)) . '...</p>
                        </div>
                    </div>';
                }
            } else {
                $html .= '
                <div class="work-card">
                    <div class="work-image">🌳 庭園設計</div>
                    <div class="work-content">
                        <h3>和風庭園設計例</h3>
                        <div class="category">📋 庭園設計</div>
                        <p>伝統的な日本庭園の美しさを現代住宅に取り入れた事例です。</p>
                    </div>
                </div>';
            }

            $html .= '
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="/works" class="hero-btn">施工実績一覧を見る</a>
            </div>
        </div>
    </section>

    <!-- お問い合わせ -->
    <section class="contact-section">
        <div class="contact-content">
            <h2>お問い合わせ</h2>
            <p>緑に関するご相談は、お気軽にお問い合わせください</p>
            <div class="contact-info">
                <div class="contact-item">
                    <h3>📞 お電話</h3>
                    <p>0596-00-0000</p>
                    <p>平日 8:00-18:00<br>土曜 8:00-17:00</p>
                </div>
                <div class="contact-item">
                    <h3>✉️ メール</h3>
                    <p>info@kokubosyokuju.geo.jp</p>
                    <p>24時間受付<br>（返信は営業時間内）</p>
                </div>
            </div>
            <a href="/contact" class="contact-btn">お問い合わせフォーム</a>
            <a href="tel:0596-00-0000" class="contact-btn">電話で相談</a>
        </div>
    </section>

    <!-- フッター -->
    <footer class="footer">
        <div class="footer-content">
            <p><strong>小久保植樹園</strong></p>
            <p>〒516-0000 三重県伊勢市○○町○○番地</p>
            <p>TEL: 0596-00-0000 | Email: info@kokubosyokuju.geo.jp</p>
            <p style="margin-top: 20px;">© 2024 小久保植樹園. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>';

            return $html;

        } catch (Exception $e) {
            return '<h1>Home Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}