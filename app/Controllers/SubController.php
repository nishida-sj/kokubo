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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
            color: #2c1810;
            text-decoration: none;
            letter-spacing: 2px;
        }

        .nav {
            display: flex;
            gap: 40px;
            list-style: none;
        }

        .nav a {
            color: #2c1810;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }

        .nav a::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 1px;
            background: #8b7355;
            transition: width 0.3s;
        }

        .nav a:hover::after {
            width: 100%;
        }

        /* メインビジュアル */
        .hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("/picture/12.jpg") center center / cover no-repeat;
        }

        .hero-bg::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.5));
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: right;
            color: white;
            padding: 0 60px;
        }

        .hero-title {
            writing-mode: vertical-rl;
            text-orientation: upright;
            font-size: 80px;
            font-weight: 900;
            letter-spacing: 20px;
            margin-bottom: 40px;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.7);
            line-height: 1.3;
        }

        .hero-subtitle {
            writing-mode: vertical-rl;
            font-size: 24px;
            font-weight: 400;
            letter-spacing: 8px;
            opacity: 0.95;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
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

        /* コンセプトセクション */
        .concept-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            margin-bottom: 80px;
        }

        .concept-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .concept-text h3 {
            font-size: 28px;
            font-weight: 600;
            color: #2c1810;
            margin-bottom: 24px;
            letter-spacing: 2px;
        }

        .concept-text p {
            font-size: 16px;
            line-height: 2;
            color: #4a4a4a;
            margin-bottom: 20px;
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
        }

        .work-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .work-item:hover img {
            transform: scale(1.1);
        }

        .work-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 20px;
            color: white;
            transform: translateY(100%);
            transition: transform 0.3s;
        }

        .work-item:hover .work-overlay {
            transform: translateY(0);
        }

        .work-overlay h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
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
        @media (max-width: 768px) {
            .hero-title {
                font-size: 50px;
                letter-spacing: 15px;
            }

            .hero-subtitle {
                font-size: 18px;
            }

            .concept-grid,
            .services-grid,
            .contact-info-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .works-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo">' . $companyName . '</a>
            <nav class="nav">
                <a href="/">ホーム</a>
                <a href="/works">施工実績</a>
                <a href="/company">会社案内</a>
                <a href="/recruit">採用情報</a>
                <a href="/contact">お問い合わせ</a>
            </nav>
        </div>
    </header>

    <!-- メインビジュアル -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title">美しい庭</h1>
            <p class="hero-subtitle">ここから映えただす</p>
        </div>
    </section>

    <!-- コンセプト -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title">Concept</h2>

            <div class="concept-grid">
                <img src="/picture/3.jpg" alt="和風庭園" class="concept-image">
                <div class="concept-text">
                    <h3>どこにもない<br>オンリーワンのものを使う<br>素材へのこだわり</h3>
                    <p>私たちは単に木を植えるだけでなく、その土地の特性を活かし、四季を通じて美しい景観を演出する空間づくりを心がけています。</p>
                    <p>お客様の暮らしに寄り添い、緑豊かな環境をお作りします。確かな技術と豊富な経験により、お客様にご満足いただける高品質な造園サービスをご提供いたします。</p>
                </div>
            </div>

            <div class="concept-grid" style="direction: rtl;">
                <img src="/picture/5.jpg" alt="施工風景" class="concept-image">
                <div class="concept-text" style="direction: ltr;">
                    <h3>地域に根ざした<br>造園業者として</h3>
                    <p>' . $companyName . 'は、伊勢市を中心とした地域密着の造園業者です。長年培った技術と経験を活かし、お客様一人ひとりのご要望に丁寧にお応えしています。</p>
                    <p>植栽から造園、お手入れまで、緑に関するあらゆるご要望にお応えいたします。</p>
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

            <div class="works-grid">
                <div class="work-item">
                    <img src="/picture/2.jpg" alt="施工実績1">
                    <div class="work-overlay">
                        <h4>花壇植栽</h4>
                    </div>
                </div>
                <div class="work-item">
                    <img src="/picture/10.jpg" alt="施工実績2">
                    <div class="work-overlay">
                        <h4>石壁と植栽</h4>
                    </div>
                </div>
                <div class="work-item">
                    <img src="/picture/11.jpg" alt="施工実績3">
                    <div class="work-overlay">
                        <h4>フェンス植栽</h4>
                    </div>
                </div>
                <div class="work-item">
                    <img src="/picture/17.jpg" alt="施工実績4">
                    <div class="work-overlay">
                        <h4>芝生施工</h4>
                    </div>
                </div>
                <div class="work-item">
                    <img src="/picture/22.jpg" alt="施工実績5">
                    <div class="work-overlay">
                        <h4>玄関デザイン</h4>
                    </div>
                </div>
                <div class="work-item">
                    <img src="/picture/24.jpg" alt="施工実績6">
                    <div class="work-overlay">
                        <h4>花木管理</h4>
                    </div>
                </div>
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

            <div class="contact-info-grid">
                <div class="contact-card">
                    <h3>📞 お電話でのご相談</h3>
                    <strong>' . $companyTel . '</strong>
                    <p>' . $companyBusinessHours . '</p>
                    <p>日曜・祝日は休業</p>
                </div>
                <div class="contact-card">
                    <h3>✉️ メールでのご相談</h3>
                    <strong>' . $companyEmail . '</strong>
                    <p>24時間受付</p>
                    <p>（返信は営業時間内）</p>
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
</body>
</html>';

        return $html;
    }
}
