<?php

class CompanyController extends Controller
{
    public function index()
    {
        // 設定値を取得
        $companyName = h(setting('company_name', '小久保植樹園'));
        $companyTel = h(setting('company_tel', '0596-00-0000'));
        $companyPostalCode = h(setting('company_postal_code', '516-0000'));
        $companyAddress = h(setting('company_address', '三重県伊勢市'));

        // 会社案内設定を取得
        $db = Db::getInstance();
        $companySettingsData = $db->fetchAll("SELECT `key`, `value` FROM company_settings");
        $cs = [];
        foreach ($companySettingsData as $row) {
            $cs[$row['key']] = $row['value'];
        }

        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会社案内 | 小久保植樹園</title>
    <meta name="description" content="小久保植樹園の会社概要、代表挨拶、沿革をご紹介。伊勢市で長年培ってきた植樹・造園の実績と信頼をお伝えします。">

    <!-- フォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Typekit -->
    <script>
      (function(d) {
        var config = {
          kitId: \'fiw6ghz\',
          scriptTimeout: 3000,
          async: true
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\\bwf-loading\\b/g,\"\")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src=\'https://use.typekit.net/\'+config.kitId+\'.js\';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!=\"complete\"&&a!=\"loaded\")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Noto Serif JP", "Hiragino Mincho ProN", "Yu Mincho", "游明朝", "Times New Roman", serif;
            line-height: 1.7;
            color: #333;
            overflow-x: hidden;
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

        /* メインコンテンツ */
        .main-content {
            margin-top: 100px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .page-header {
            background: linear-gradient(135deg, #19448e 0%, #2c5aa0 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
        }

        .page-title {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .page-subtitle {
            font-size: 18px;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .section {
            margin-bottom: 80px;
        }

        .section-title {
            font-size: 32px;
            color: #19448e;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .section-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .greeting-section {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
            align-items: center;
        }

        .greeting-text h3 {
            font-size: 24px;
            color: #19448e;
            margin-bottom: 20px;
        }

        .greeting-text p {
            margin-bottom: 20px;
            line-height: 1.8;
            font-size: 16px;
        }

        .president-info {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .president-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #19448e 0%, #4a90e2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 60px;
            color: white;
        }

        .president-name {
            font-size: 20px;
            font-weight: 600;
            color: #19448e;
            margin-bottom: 5px;
        }

        .president-title {
            font-size: 14px;
            color: #666;
        }

        .company-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .info-item {
            display: flex;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #19448e;
            min-width: 120px;
            font-size: 16px;
        }

        .info-value {
            flex: 1;
            font-size: 16px;
        }

        .history-timeline {
            position: relative;
            padding-left: 30px;
        }

        .history-timeline::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #19448e;
        }

        .history-item {
            position: relative;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .history-item::before {
            content: "";
            position: absolute;
            left: -22px;
            top: 25px;
            width: 12px;
            height: 12px;
            background: #19448e;
            border-radius: 50%;
            border: 3px solid white;
        }

        .history-year {
            font-size: 20px;
            font-weight: 600;
            color: #19448e;
            margin-bottom: 10px;
        }

        .history-event {
            font-size: 16px;
            line-height: 1.6;
        }

        /* フッター */
        .footer {
            background: #1a237e;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .footer p {
            margin: 8px 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* レスポンシブ */
        @media (max-width: 768px) {
            .header-container {
                padding: 20px;
            }

            .nav {
                display: none;
            }

            .page-title {
                font-size: 32px;
            }

            .greeting-section {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .company-info {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 40px 15px;
            }
        }

        /* レスポンシブ - ヘッダー */
        @media (max-width: 1024px) {
            .header-left .nav,
            .header-right .nav {
                display: none;
            }

            .logo-center {
                position: static;
                transform: none;
            }

            .header-icon {
                margin-left: auto;
            }

            .menu-btn {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 15px 20px;
            }

            .header-left .nav,
            .header-right .nav {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(25, 68, 142, 0.95);
                backdrop-filter: blur(10px);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 30px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .nav.is-open {
                transform: translateX(0);
            }

            .nav a {
                font-size: 20px;
                font-weight: 600;
                padding: 15px 0;
                color: #fff;
            }

            body.menu-open {
                overflow: hidden;
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

            <a href="/" class="logo-center">小久保植樹園</a>

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

    <div class="main-content">
        <!-- ページヘッダー -->
        <section class="page-header">
            <h1 class="page-title">会社案内</h1>
            <p class="page-subtitle">伊勢の地で培った信頼と実績をご紹介いたします</p>
        </section>

        <div class="container">
            <!-- 代表挨拶 -->
            <section class="section">
                <h2 class="section-title">' . h($cs['greeting_title'] ?? '代表挨拶') . '</h2>
                <div class="section-content">
                    <div class="greeting-section">
                        <div class="greeting-text">' . nl2br(h($cs['greeting_content'] ?? '')) . '</div>
                    </div>
                </div>
            </section>

            <!-- 会社概要 -->
            <section class="section">
                <h2 class="section-title">会社概要</h2>
                <div class="section-content">
                    <div class="company-info">
                        <div>
                            <div class="info-item">
                                <div class="info-label">会社名</div>
                                <div class="info-value">' . h($cs['overview_name'] ?? $companyName) . '</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">代表者</div>
                                <div class="info-value">' . h($cs['overview_representative'] ?? '') . '</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">設立</div>
                                <div class="info-value">' . h($cs['overview_established'] ?? '') . '</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">従業員数</div>
                                <div class="info-value">' . h($cs['overview_employees'] ?? '') . '</div>
                            </div>
                        </div>
                        <div>
                            <div class="info-item">
                                <div class="info-label">事業内容</div>
                                <div class="info-value">' . nl2br(h($cs['overview_business'] ?? '')) . '</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">対応エリア</div>
                                <div class="info-value">' . h($cs['overview_area'] ?? '') . '</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 沿革 -->
            <section class="section">
                <h2 class="section-title">沿革</h2>
                <div class="section-content">
                    <div class="history-timeline">
                        <div class="history-item">
                            <div class="history-year">1984年</div>
                            <div class="history-event">小久保植樹園を創業。個人宅の庭木管理からスタート</div>
                        </div>
                        <div class="history-item">
                            <div class="history-year">1990年</div>
                            <div class="history-event">マンション・公共施設の植栽工事事業を開始</div>
                        </div>
                        <div class="history-item">
                            <div class="history-year">1995年</div>
                            <div class="history-event">造園設計事業を開始。庭園の設計から施工まで一貫対応</div>
                        </div>
                        <div class="history-item">
                            <div class="history-year">2000年</div>
                            <div class="history-event">伊勢市内での実績が評価され、事業拡大</div>
                        </div>
                        <div class="history-item">
                            <div class="history-year">2010年</div>
                            <div class="history-event">年間管理契約サービスを開始。保養所等の継続管理を本格化</div>
                        </div>
                        <div class="history-item">
                            <div class="history-year">2020年</div>
                            <div class="history-event">環境保全への取り組みを強化。持続可能な緑化事業を推進</div>
                        </div>
                        <div class="history-item">
                            <div class="history-year">2024年</div>
                            <div class="history-event">創業40周年。地域に根ざした信頼の実績を築く</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- フッター -->
    <footer class="footer">
        <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
            <h3 style="font-size: 28px; margin-bottom: 20px; color: #fff;">' . $companyName . '</h3>
            <p>〒' . $companyPostalCode . ' ' . $companyAddress . '</p>
            <p>TEL: ' . $companyTel . '</p>
            <p style="margin-top: 20px; opacity: 0.8;">© ' . date('Y') . ' ' . $companyName . '. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const menuBtn = document.getElementById("menuBtn");
        const nav = document.querySelector(".header-right .nav");

        menuBtn.addEventListener("click", function() {
            menuBtn.classList.toggle("is-active");
            nav.classList.toggle("is-open");
            document.body.classList.toggle("menu-open");
        });

        nav.querySelectorAll("a").forEach(function(link) {
            link.addEventListener("click", function() {
                menuBtn.classList.remove("is-active");
                nav.classList.remove("is-open");
                document.body.classList.remove("menu-open");
            });
        });
    </script>
</body>
</html>';

        return $html;
    }
}