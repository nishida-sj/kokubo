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
    <meta name="keywords" content="伊勢市,植樹園,造園,植栽,庭木,剪定,小久保植樹園">

    <!-- CSS読み込み -->
    <link rel="stylesheet" href="' . asset_url('css/style.css') . '">

    <!-- フォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hiragino+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo">
                <span class="logo-icon">🌿</span>
                小久保植樹園
            </a>
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
            <p class="subtitle">信頼と技術で築く美しい庭園</p>
            <p class="description">伊勢市の植樹・造園専門業者として、お客様の想いを形にします。<br>四季を通じて美しい景観を演出する空間づくりを心がけています。</p>
            <a href="/contact" class="hero-btn">お問い合わせ</a>
        </div>
    </section>

    <!-- コンセプト -->
    <section class="section concept">
        <div class="container">
            <h2 class="section-title">私たちのコンセプト</h2>
            <p class="section-subtitle">技術と経験で、お客様の想いを美しい庭園に</p>
            <div class="concept-content">
                <div class="concept-text">
                    <h3>地域に根ざした造園業者として</h3>
                    <p>小久保植樹園は、伊勢市を中心とした地域密着の造園業者です。長年培った技術と経験を活かし、お客様一人ひとりのご要望に丁寧にお応えしています。</p>
                    <p>私たちは単に木を植えるだけでなく、その土地の特性を活かし、四季を通じて美しい景観を演出する空間づくりを心がけています。お客様の暮らしに寄り添い、緑豊かな環境をお作りします。</p>
                    <p>確かな技術と豊富な経験により、お客様にご満足いただける高品質な造園サービスをご提供いたします。</p>
                </div>
                <div class="concept-visual">
                    <span class="main-icon">🌳</span>
                    <h4>自然との調和</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- サービス -->
    <section class="section services">
        <div class="container">
            <h2 class="section-title">事業案内</h2>
            <p class="section-subtitle">植栽から管理まで、緑に関するあらゆるニーズにお応えします</p>
            <div class="services-grid">
                <div class="service-card">
                    <span class="service-icon">🏡</span>
                    <h3>庭園設計</h3>
                    <p>お客様のご要望やライフスタイルに合わせた庭園の設計・施工を行います。和風・洋風問わず、美しく機能的な庭園をお作りします。土地の特性を活かした設計で、長く愛される空間をご提案いたします。</p>
                </div>
                <div class="service-card">
                    <span class="service-icon">🌱</span>
                    <h3>植栽工事</h3>
                    <p>住宅やマンション、公共施設の植栽工事を承ります。適切な植物選びから施工まで一貫して対応。気候や土壌条件を考慮し、その場所に最適な樹種をご提案し、美しい緑空間を創造します。</p>
                </div>
                <div class="service-card">
                    <span class="service-icon">🌿</span>
                    <h3>樹木管理</h3>
                    <p>定期的な剪定・管理で美しい緑を維持します。樹木の健康状態をチェックし、適切なケアを提供。病害虫の防除や施肥管理も行い、樹木が長期間健康で美しい状態を保てるようサポートします。</p>
                </div>
                <div class="service-card">
                    <span class="service-icon">✂️</span>
                    <h3>剪定作業</h3>
                    <p>樹種に応じた適切な剪定で健康な樹木を育てます。美しい樹形づくりもお任せください。時期や方法を見極め、樹木本来の美しさを引き出しながら、安全性も確保した剪定を行います。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 施工実績 -->
    <section class="section works">
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
                            <div class="work-category">📋 ' . h($work['category_name']) . '</div>
                            <p>' . h(mb_substr($work['description'], 0, 80)) . '...</p>
                        </div>
                    </div>';
                }
            } else {
                // ダミーデータ表示
                $dummyWorks = [
                    ['title' => '伊勢市内 和風庭園設計', 'category' => '庭園設計', 'description' => '伝統的な日本庭園の美しさを現代住宅に取り入れた和風庭園です。四季を通じて楽しめる植栽配置にこだわりました。'],
                    ['title' => 'マンション植栽工事', 'category' => '植栽工事', 'description' => '新築マンションのエントランス周辺とお庭の植栽工事を行いました。住民の皆様に愛される緑豊かな環境を目指しました。'],
                    ['title' => '公園樹木管理', 'category' => '樹木管理', 'description' => '市立公園の樹木管理を継続的に行っています。安全性と美観を両立した管理を心がけています。'],
                ];

                foreach ($dummyWorks as $work) {
                    $html .= '
                    <div class="work-card">
                        <div class="work-image">🌳 ' . h($work['category']) . '</div>
                        <div class="work-content">
                            <h3>' . h($work['title']) . '</h3>
                            <div class="work-category">📋 ' . h($work['category']) . '</div>
                            <p>' . h($work['description']) . '</p>
                        </div>
                    </div>';
                }
            }

            $html .= '
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="/works" class="hero-btn">施工実績一覧を見る</a>
            </div>
        </div>
    </section>

    <!-- お問い合わせ -->
    <section class="section contact-section">
        <div class="container">
            <div class="contact-content">
                <h2 class="section-title">お問い合わせ</h2>
                <p class="section-subtitle">緑に関するご相談は、お気軽にお問い合わせください</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <h3>📞 お電話でのご相談</h3>
                        <p><strong>0596-00-0000</strong></p>
                        <p>平日 8:00-18:00<br>土曜 8:00-17:00</p>
                        <p>日曜・祝日は休業</p>
                    </div>
                    <div class="contact-item">
                        <h3>✉️ メールでのご相談</h3>
                        <p><strong>info@kokubosyokuju.geo.jp</strong></p>
                        <p>24時間受付<br>（返信は営業時間内）</p>
                        <p>お気軽にご相談ください</p>
                    </div>
                </div>
                <div class="contact-buttons">
                    <a href="/contact" class="contact-btn">お問い合わせフォーム</a>
                    <a href="tel:0596-00-0000" class="contact-btn">📞 電話で相談</a>
                </div>
            </div>
        </div>
    </section>

    <!-- フッター -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <h3>小久保植樹園</h3>
                <p>〒516-0000 三重県伊勢市○○町○○番地</p>
                <p>TEL: 0596-00-0000 | Email: info@kokubosyokuju.geo.jp</p>
                <p>営業時間: 平日 8:00-18:00 / 土曜 8:00-17:00</p>
                <div class="copyright">
                    <p>© 2024 小久保植樹園. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // スムーススクロール
        document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
            anchor.addEventListener(\'click\', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute(\'href\')).scrollIntoView({
                    behavior: \'smooth\'
                });
            });
        });

        // フェードインアニメーション
        const observerOptions = {
            threshold: 0.1,
            rootMargin: \'0px 0px -50px 0px\'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = \'1\';
                    entry.target.style.transform = \'translateY(0)\';
                }
            });
        }, observerOptions);

        // 各セクションにフェードイン効果を適用
        document.querySelectorAll(\'.section\').forEach(section => {
            section.style.opacity = \'0\';
            section.style.transform = \'translateY(30px)\';
            section.style.transition = \'opacity 0.6s ease, transform 0.6s ease\';
            observer.observe(section);
        });
    </script>
</body>
</html>';

            return $html;

        } catch (Exception $e) {
            return '<h1>Home Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}