<?php

class CompanyController extends Controller
{
    public function index()
    {
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
            background: rgba(80, 80, 80, 0.4);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 100px;
            max-width: none;
            margin: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #fff;
            font-family: "fiw6ghz", "Noto Serif JP", "Hiragino Mincho ProN", "Yu Mincho", "游明朝", serif;
            font-weight: 500;
            font-size: 32px;
        }

        .nav {
            display: flex;
            list-style: none;
            gap: 50px;
            align-items: center;
        }

        .nav a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            font-size: 18px;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav a:hover {
            color: #ccc;
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
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo">
                小久保植樹園
            </a>
            <nav class="nav">
                <a href="/">ホーム</a>
                <a href="/works">施工実績</a>
                <a href="/company">会社案内</a>
                <a href="/recruit">採用情報</a>
                <a href="/contact">お問い合わせ</a>
            </nav>
        </div>
    </header>

    <div class="main-content">
        <!-- ページヘッダー -->
        <section class="page-header">
            <h1 class="page-title">🏢 会社案内</h1>
            <p class="page-subtitle">伊勢の地で培った信頼と実績をご紹介いたします</p>
        </section>

        <div class="container">
            <!-- 代表挨拶 -->
            <section class="section">
                <h2 class="section-title">代表挨拶</h2>
                <div class="section-content">
                    <div class="greeting-section">
                        <div class="greeting-text">
                            <h3>緑豊かな環境づくりを通じて、地域社会に貢献してまいります</h3>
                            <p>小久保植樹園は、伊勢の地で長年にわたり植樹・造園業に携わってまいりました。私たちは単に木を植えるだけでなく、その土地の特性を活かし、四季を通じて美しい景観を演出する空間づくりを心がけています。</p>
                            <p>お客様の暮らしに寄り添い、緑豊かな環境をお作りすることで、地域社会の発展に貢献できるよう努めております。確かな技術と豊富な経験により、お客様にご満足いただける高品質なサービスをご提供いたします。</p>
                            <p>今後とも変わらぬご愛顧を賜りますよう、よろしくお願い申し上げます。</p>
                        </div>
                        <div class="president-info">
                            <div class="president-photo">👨‍🌾</div>
                            <div class="president-name">小久保 太郎</div>
                            <div class="president-title">代表取締役</div>
                        </div>
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
                                <div class="info-value">小久保植樹園</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">代表者</div>
                                <div class="info-value">小久保 太郎</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">設立</div>
                                <div class="info-value">1984年</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">所在地</div>
                                <div class="info-value">〒516-0000 三重県伊勢市</div>
                            </div>
                        </div>
                        <div>
                            <div class="info-item">
                                <div class="info-label">電話番号</div>
                                <div class="info-value">0596-00-0000</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">事業内容</div>
                                <div class="info-value">植栽工事・造園・庭木管理・剪定作業</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">営業時間</div>
                                <div class="info-value">平日 8:00-18:00 / 土曜 8:00-17:00</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">定休日</div>
                                <div class="info-value">日曜・祝日</div>
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
            <h3 style="font-size: 28px; margin-bottom: 20px; color: #fff;">小久保植樹園</h3>
            <p>〒516-0000 三重県伊勢市</p>
            <p>TEL: 0596-00-0000</p>
            <p style="margin-top: 20px; opacity: 0.8;">© 2024 小久保植樹園. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>';

        return $html;
    }
}