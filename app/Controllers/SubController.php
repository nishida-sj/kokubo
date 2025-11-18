<?php

class SubController extends Controller
{
    public function index()
    {
        // 設定値を取得
        $companyName = h(setting('company_name', '小久保植樹園'));
        $companyTel = h(setting('company_tel', '0596-00-0000'));
        $companyPostalCode = h(setting('company_postal_code', '516-0000'));
        $companyAddress = h(setting('company_address', '三重県伊勢市'));
        $companyEmail = h(setting('company_email', 'info@kokubosyokuju.geo.jp'));
        $companyBusinessHours = h(setting('company_business_hours', '平日 8:00-18:00 / 土曜 8:00-17:00'));
        $siteDescription = h(setting('site_description', '伊勢市の植樹園。植栽工事・庭園設計・樹木管理を手がける地域密着の造園業者です。'));

        // データベースから施工実績を取得
        $db = Db::getInstance();
        $works = $db->fetchAll("
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
    <title>' . $companyName . ' | ' . $siteDescription . '</title>
    <meta name="description" content="' . $siteDescription . '">

    <!-- フォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <style>
        /* リセット */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Noto Serif JP", "Hiragino Mincho ProN", "Yu Mincho", "游明朝", serif;
            line-height: 1.8;
            color: #333;
            background: #f5f2ed;
        }

        /* ヘッダー */
        .header {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            gap: 35px;
            align-items: center;
        }

        .logo-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 20px;
            font-weight: 600;
            color: #2c1810;
            letter-spacing: 3px;
            text-decoration: none;
        }

        .header-right {
            display: flex;
            gap: 35px;
            align-items: center;
        }

        .nav {
            display: flex;
            gap: 35px;
            list-style: none;
        }

        .nav a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            font-weight: 400;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: #8b7355;
        }

        .header-icon {
            width: 35px;
            height: 35px;
            background: #2c1810;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .header-icon:hover {
            background: #8b7355;
        }

        /* ハンバーガーメニューボタン */
        .menu-btn {
            display: none;
            flex-direction: column;
            justify-content: center;
            width: 30px;
            height: 30px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
        }

        .menu-line {
            display: block;
            width: 25px;
            height: 3px;
            background: #333;
            margin: 3px 0;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .menu-btn.is-active .menu-line {
            background: #fff;
        }

        .menu-btn.is-active .menu-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .menu-btn.is-active .menu-line:nth-child(2) {
            opacity: 0;
        }

        .menu-btn.is-active .menu-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* メインビジュアル */
        .hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            overflow: hidden;
            margin-top: 95px;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .hero-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            animation: slideShow 15s infinite;
        }

        .hero-slide:nth-child(1) {
            background-image: url("/picture/12.jpg");
            animation-delay: 0s;
        }

        .hero-slide:nth-child(2) {
            background-image: url("/picture/3.jpg");
            animation-delay: 5s;
        }

        .hero-slide:nth-child(3) {
            background-image: url("/picture/5.jpg");
            animation-delay: 10s;
        }

        @keyframes slideShow {
            0% { opacity: 0; }
            6.67% { opacity: 1; }
            33.33% { opacity: 1; }
            40% { opacity: 0; }
            100% { opacity: 0; }
        }

        .hero-text-wrapper {
            position: relative;
            z-index: 2;
            background: white;
            padding: 60px 40px;
            margin-right: 80px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .hero-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            display: flex;
            gap: 30px;
        }

        .hero-subtitle {
            font-size: 22px;
            font-weight: 400;
            color: #2c1810;
            letter-spacing: 8px;
            line-height: 1.8;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 700;
            color: #2c1810;
            letter-spacing: 12px;
            line-height: 1.6;
        }

        /* セクション共通 */
        .section {
            padding: 120px 40px;
        }

        .section-light {
            background: #fff;
        }

        .section-dark {
            background: #f5f2ed;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 36px;
            font-weight: 600;
            color: #2c1810;
            margin-bottom: 60px;
            text-align: center;
            letter-spacing: 4px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-bottom: 60px;
            line-height: 1.6;
        }

        /* コンセプトセクション */
        .concept-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            margin-top: 60px;
        }

        .concept-text {
            font-size: 16px;
            line-height: 1.8;
        }

        .concept-text h3 {
            font-size: 28px;
            color: #2c1810;
            margin-bottom: 24px;
            font-weight: 600;
        }

        .concept-text p {
            margin-bottom: 20px;
            color: #555;
        }

        .concept-images {
            display: grid;
            grid-template-rows: 1fr auto;
            gap: 20px;
        }

        .concept-image-main {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .concept-image-main img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .concept-image-main:hover img {
            transform: scale(1.05);
        }

        .concept-image-sub {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .concept-image-sub img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .concept-image-sub img:hover {
            transform: translateY(-5px);
        }

        /* サービスセクション */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 60px;
        }

        .service-item {
            background: white;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .service-item:hover {
            transform: translateY(-5px);
        }

        .service-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .service-content {
            padding: 30px;
        }

        .service-content h3 {
            font-size: 22px;
            font-weight: 600;
            color: #2c1810;
            margin-bottom: 16px;
            letter-spacing: 1px;
        }

        .service-content p {
            font-size: 15px;
            line-height: 1.9;
            color: #555;
        }

        /* 施工実績セクション */
        .works-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 60px;
        }

        .work-item {
            position: relative;
            overflow: hidden;
            border-radius: 4px;
            cursor: pointer;
            aspect-ratio: 1;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .work-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .work-item:hover img {
            transform: scale(1.05);
        }

        .work-category {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.95);
            color: #2c1810;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            z-index: 1;
        }

        .work-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0.4));
            padding: 25px 20px 20px;
            color: white;
        }

        .work-title h4 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        /* お問い合わせセクション */
        .contact-section {
            background: linear-gradient(135deg, #8b7355 0%, #6b5644 100%);
            color: white;
            text-align: center;
        }

        .contact-section .section-title {
            color: white;
        }

        .contact-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            max-width: 900px;
            margin: 60px auto 40px;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-card h3 {
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .contact-card p {
            font-size: 16px;
            margin: 10px 0;
        }

        .contact-card strong {
            font-size: 24px;
            display: block;
            margin: 15px 0;
        }

        .contact-btn {
            display: inline-block;
            background: white;
            color: #8b7355;
            padding: 18px 50px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 30px;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        /* フッター */
        .footer {
            background: #2c1810;
            color: rgba(255, 255, 255, 0.8);
            padding: 60px 40px 30px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer h3 {
            font-size: 24px;
            color: white;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }

        .footer p {
            margin: 10px 0;
            font-size: 14px;
        }

        .footer-copyright {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 13px;
        }

        /* レスポンシブ */
        @media (max-width: 1024px) {
            .header-left,
            .header-right .nav,
            .header-icon {
                display: none;
            }

            .logo-center {
                position: static;
                transform: none;
            }

            .menu-btn {
                display: flex;
                margin-left: auto;
            }

            .header-right {
                justify-content: flex-end;
            }

            /* モバイルメニューオーバーレイ */
            .nav-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(44, 24, 16, 0.95);
                backdrop-filter: blur(10px);
                display: none;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 30px;
                z-index: 999;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .nav-overlay.is-open {
                display: flex;
                opacity: 1;
            }

            .nav-overlay a {
                color: #fff;
                text-decoration: none;
                font-size: 20px;
                font-weight: 600;
                padding: 15px 0;
            }

            body.menu-open {
                overflow: hidden;
            }
        }

        @media (max-width: 768px) {
            .hero {
                height: 70vh;
                margin-top: 85px;
            }

            .hero-text-wrapper {
                margin-right: 30px;
                padding: 40px 8px;
                width: 90px;
            }

            .hero-text {
                gap: 25px;
            }

            .hero-title {
                font-size: 36px;
                letter-spacing: 8px;
                -webkit-text-size-adjust: 100%;
            }

            .hero-subtitle {
                font-size: 18px;
                letter-spacing: 6px;
                -webkit-text-size-adjust: 100%;
            }

            .concept-grid,
            .services-grid,
            .contact-info-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .concept-image-main img {
                height: 300px;
            }

            .concept-image-sub img {
                height: 150px;
            }

            .works-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .section-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <div class="header-left">
                <nav class="nav">
                    <a href="/">ホーム</a>
                    <a href="/works">施工実績</a>
                    <a href="/company">会社案内</a>
                </nav>
            </div>

            <a href="/" class="logo-center">' . $companyName . '</a>

            <div class="header-right">
                <nav class="nav">
                    <a href="/recruit">採用情報</a>
                    <a href="/contact">お問い合わせ</a>
                </nav>
                <a href="#" class="header-icon">f</a>
                <button class="menu-btn" id="menuBtn">
                    <span class="menu-line"></span>
                    <span class="menu-line"></span>
                    <span class="menu-line"></span>
                </button>
            </div>
        </div>
    </header>

    <!-- モバイルナビゲーション -->
    <nav class="nav-overlay" id="navOverlay">
        <a href="/">ホーム</a>
        <a href="/works">施工実績</a>
        <a href="/company">会社案内</a>
        <a href="/recruit">採用情報</a>
        <a href="/contact">お問い合わせ</a>
    </nav>

    <!-- メインビジュアル -->
    <section class="hero">
        <div class="hero-bg">
            <div class="hero-slide"></div>
            <div class="hero-slide"></div>
            <div class="hero-slide"></div>
        </div>
        <div class="hero-text-wrapper">
            <div class="hero-text">
                <p class="hero-subtitle">緑豊かな</p>
                <h1 class="hero-title">美しい庭造</h1>
            </div>
        </div>
    </section>

    <!-- コンセプト -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title">私たちのコンセプト</h2>
            <p class="section-subtitle">技術と経験で、お客様の想いを美しい庭園に</p>

            <div class="concept-grid">
                <div class="concept-text">
                    <h3>地域に根ざした造園業者として</h3>
                    <p>' . $companyName . 'は、伊勢市を中心とした地域密着の造園業者です。長年培った技術と経験を活かし、お客様一人ひとりのご要望に丁寧にお応えしています。</p>
                    <p>私たちは単に木を植えるだけでなく、その土地の特性を活かし、四季を通じて美しい景観を演出する空間づくりを心がけています。お客様の暮らしに寄り添い、緑豊かな環境をお作りします。</p>
                    <p>確かな技術と豊富な経験により、お客様にご満足いただける高品質な造園サービスをご提供いたします。</p>
                </div>
                <div class="concept-images">
                    <div class="concept-image-main">
                        <img src="/picture/3.jpg" alt="和風庭園の施工例">
                    </div>
                    <div class="concept-image-sub">
                        <img src="/picture/24.jpg" alt="美しい花々">
                        <img src="/picture/26.jpg" alt="植樹園の風景">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 事業内容 -->
    <section class="section section-dark">
        <div class="container">
            <h2 class="section-title">事業内容</h2>

            <div class="services-grid">
                <div class="service-item">
                    <img src="/picture/1.jpg" alt="植栽・造園" class="service-image">
                    <div class="service-content">
                        <h3>植栽・造園</h3>
                        <p>植木の植栽、芝生の施工、庭石・景石・灯篭の設置・撤去、緑化対策など、美しい緑空間の創造を行います。お客様のご要望に応じた庭づくりをご提案いたします。</p>
                    </div>
                </div>

                <div class="service-item">
                    <img src="/picture/13.jpg" alt="お手入れ・管理" class="service-image">
                    <div class="service-content">
                        <h3>お手入れ・管理</h3>
                        <p>植木の剪定（お手入れ）、庭木・生垣の刈込み、芝刈り（草刈り）、草取り、保養所等の年間管理を承ります。定期的な管理で美しい庭を維持します。</p>
                    </div>
                </div>

                <div class="service-item">
                    <img src="/picture/30.jpg" alt="防除・特殊作業" class="service-image">
                    <div class="service-content">
                        <h3>防除・特殊作業</h3>
                        <p>植木の消毒、防草対策（防草シート設置）、ハチの巣駆除、立木の伐採など専門的な作業に対応します。安全第一で作業いたします。</p>
                    </div>
                </div>

                <div class="service-item">
                    <img src="/picture/27.jpg" alt="施工・その他" class="service-image">
                    <div class="service-content">
                        <h3>施工・その他</h3>
                        <p>植木の移植、山砂・砂利の施工・運搬、駐車場の施工、遊具の設置、お墓の管理、門松の施工まで幅広く対応いたします。</p>
</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 施工実績 -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title">施工実績</h2>

            <div class="works-grid">';

        foreach ($works as $work) {
            // 画像パスの自動修正
            $imageUrl = '/picture/2.jpg'; // デフォルト画像
            if ($work['main_image']) {
                $imageUrl = $work['main_image'];
                // /で始まるが/uploads/を含まない場合、/uploadsを追加
                if (strpos($imageUrl, '/uploads/') === false && strpos($imageUrl, '/') === 0) {
                    $imageUrl = '/uploads' . $imageUrl;
                }
            }
            $imageUrl = h($imageUrl);
            $title = h($work['title']);
            $category = h($work['category_name'] ?? '');
            $slug = h($work['slug']);

            $html .= '
                <a href="/works/' . $slug . '" class="work-item">
                    <img src="' . $imageUrl . '" alt="' . $title . '">';

            if ($category) {
                $html .= '
                    <div class="work-category">' . $category . '</div>';
            }

            $html .= '
                    <div class="work-title">
                        <h4>' . $title . '</h4>
                    </div>
                </a>';
        }

        $html .= '
            </div>

            <div style="text-align: center; margin-top: 50px;">
                <a href="/works" class="contact-btn" style="background: #8b7355; color: white;">施工実績一覧を見る</a>
            </div>
        </div>
    </section>

    <!-- お問い合わせ -->
    <section class="section contact-section">
        <div class="container">
            <h2 class="section-title">お問い合わせ</h2>
            <p style="font-size: 18px; margin-bottom: 40px;">緑に関するご相談は、お気軽にお問い合わせください</p>

            <div style="max-width: 600px; margin: 0 auto 40px;">
                <div class="contact-card">
                    <h3>📞 お電話でのご相談</h3>
                    <strong>' . $companyTel . '</strong>
                    <p>' . $companyBusinessHours . '</p>
                    <p>日曜・祝日は休業</p>
                </div>
            </div>

            <a href="/contact" class="contact-btn">お問い合わせフォームへ</a>
        </div>
    </section>

    <!-- フッター -->
    <footer class="footer">
        <div class="footer-content">
            <h3>' . $companyName . '</h3>
            <p>〒' . $companyPostalCode . ' ' . $companyAddress . '</p>
            <p>TEL: ' . $companyTel . ' | Email: ' . $companyEmail . '</p>
            <p>営業時間: ' . $companyBusinessHours . '</p>
            <div class="footer-copyright">
                <p>© ' . date('Y') . ' ' . $companyName . '. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // ハンバーガーメニュー
        const menuBtn = document.getElementById("menuBtn");
        const navOverlay = document.getElementById("navOverlay");

        menuBtn.addEventListener("click", function() {
            menuBtn.classList.toggle("is-active");
            navOverlay.classList.toggle("is-open");
            document.body.classList.toggle("menu-open");
        });

        // オーバーレイ内のリンクをクリックしたらメニューを閉じる
        navOverlay.querySelectorAll("a").forEach(function(link) {
            link.addEventListener("click", function() {
                menuBtn.classList.remove("is-active");
                navOverlay.classList.remove("is-open");
                document.body.classList.remove("menu-open");
            });
        });
    </script>
</body>
</html>';

        return $html;
    }
}
