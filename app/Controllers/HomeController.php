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

            // データベースにデータがない場合はダミーデータを使用
            if (empty($featuredWorks)) {
                $featuredWorks = [
                    [
                        'slug' => 'modern-garden-1',
                        'title' => 'モダン住宅の外構植栽',
                        'description' => '砂利とシンボルツリーを配置したシンプルでモダンな外構デザイン。お手入れしやすく美しい空間を実現しました。',
                        'main_image' => 'assets/img/works/1.jpg',
                        'category_name' => '住宅外構',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'japanese-garden',
                        'title' => '和風庭園の植栽',
                        'description' => '赤いモミジと庭石を配置した趣のある和風庭園。四季折々の美しさを楽しめる設計です。',
                        'main_image' => 'assets/img/works/3.jpg',
                        'category_name' => '和風庭園',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'lawn-installation',
                        'title' => '人工芝の施工',
                        'description' => '美しい緑の芝生を施工。一年中青々とした美しい庭を維持できます。',
                        'main_image' => 'assets/img/works/17.jpg',
                        'category_name' => '芝生施工',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'stone-wall-garden',
                        'title' => '石壁と植栽の調和',
                        'description' => 'モダンな石壁に低木とグリーンを配置。メンテナンスしやすく美しい外構です。',
                        'main_image' => 'assets/img/works/10.jpg',
                        'category_name' => '外構デザイン',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'entrance-planting',
                        'title' => '玄関周りの植栽デザイン',
                        'description' => '玄関を彩る植栽デザイン。来客を温かく迎える緑の空間を演出しました。',
                        'main_image' => 'assets/img/works/22.jpg',
                        'category_name' => '玄関植栽',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'fence-planting',
                        'title' => 'フェンス沿いの植栽',
                        'description' => 'フェンス沿いにシンボルツリーと低木を配置。プライバシーを守りながら緑豊かな空間に。',
                        'main_image' => 'assets/img/works/11.jpg',
                        'category_name' => 'フェンス植栽',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ]
                ];
            }

            $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>小久保植樹園 | 伊勢市の植樹・造園専門業者</title>
    <meta name="description" content="伊勢市の植樹園。植栽工事・庭園設計・樹木管理を手がける地域密着の造園業者です。緑豊かな空間づくりをお手伝いします。">
    <meta name="keywords" content="伊勢市,植樹園,造園,植栽,庭木,剪定,小久保植樹園">

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
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\\bwf-loading\\b/g,\"\")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src=\'https://use.typekit.net/\'+config.kitId+\'.js\';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    </script>

    <style>
        /* 小久保植樹園 メインスタイル - 参考サイト準拠 */

        /* リセット・ベース */
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

        .logo .logo-icon {
            font-size: 36px;
            margin-right: 10px;
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

        .nav a::after {
            content: "";
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: #19448e;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav a:hover::after {
            width: 100%;
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
            background: #fff;
            margin: 3px 0;
            transition: all 0.3s ease;
            transform-origin: center;
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

        /* レスポンシブ表示制御 */
        .sponly {
            display: none;
        }

        .pconly {
            display: block;
        }

        /* メインビジュアル */
        .hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("/assets/img/item/top.jpg");
            background-size: cover;
            background-position: center;
            color: white;
            text-align: left;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(25, 68, 142, 0.7) 0%, rgba(44, 90, 160, 0.6) 50%, rgba(74, 144, 226, 0.5) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            width: 100%;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeInUp 1s ease-out;
        }

        .hero-text {
            flex: 1;
            max-width: 600px;
        }

        .hero-text h1 {
            font-family: "Noto Serif JP", "Hiragino Mincho ProN", "Yu Mincho", "游明朝", serif;
            font-size: 48px;
            font-weight: 600;
            margin-bottom: 24px;
            line-height: 1.2;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
        }

        .hero-text .subtitle {
            font-size: 20px;
            margin-bottom: 16px;
            opacity: 0.95;
            font-weight: 300;
        }

        .hero-text .description {
            font-size: 16px;
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-company {
            position: relative;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-family: "Noto Serif JP", "Hiragino Mincho ProN", "Yu Mincho", "游明朝", serif;
            font-size: 60px;
            font-weight: 400;
            letter-spacing: 12px;
            opacity: 0.95;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6);
            margin-left: 40px;
            color: #ffffff;
        }

        .hero-btn {
            display: inline-block;
            background: #fff;
            color: #19448e;
            padding: 18px 48px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
            background: #f8f9fa;
        }

        /* セクション共通 */
        .section {
            padding: 100px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            font-family: "Noto Serif JP", "Hiragino Mincho ProN", "Yu Mincho", "游明朝", serif;
            font-size: 42px;
            font-weight: 600;
            color: #19448e;
            margin-bottom: 20px;
            position: relative;
        }

        .section-title::after {
            content: "";
            width: 80px;
            height: 4px;
            background: #19448e;
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        .section-subtitle {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-bottom: 60px;
            line-height: 1.6;
        }

        /* コンセプトセクション */
        .concept {
            background: #f8f9fa;
        }

        .concept-content {
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
            color: #19448e;
            margin-bottom: 24px;
            font-weight: 600;
        }

        .concept-text p {
            margin-bottom: 20px;
            color: #555;
        }

        .concept-visual {
            text-align: center;
            padding: 60px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 20px;
            position: relative;
        }

        .concept-visual .main-icon {
            font-size: 120px;
            color: #19448e;
            margin-bottom: 20px;
            display: block;
        }

        .concept-visual h4 {
            font-size: 24px;
            color: #19448e;
            font-weight: 600;
        }

        /* サービスセクション */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .service-card {
            background: #fff;
            padding: 50px 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #19448e, #4a90e2);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .service-icon {
            font-size: 80px;
            color: #19448e;
            margin-bottom: 24px;
            display: block;
        }

        .service-card h3 {
            font-size: 24px;
            color: #19448e;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .service-card p {
            color: #666;
            line-height: 1.7;
            font-size: 15px;
        }

        /* 実績セクション */
        .works {
            background: #f8f9fa;
        }

        .works-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .work-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .work-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .work-image {
            height: 250px;
            background: linear-gradient(135deg, #19448e 0%, #4a90e2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .work-content {
            padding: 30px;
        }

        .work-content h3 {
            font-size: 20px;
            color: #19448e;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .work-category {
            color: #666;
            font-size: 14px;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .work-content p {
            color: #555;
            line-height: 1.6;
            font-size: 15px;
        }

        /* お問い合わせセクション */
        .contact-section {
            background: linear-gradient(135deg, #19448e 0%, #2c5aa0 100%);
            color: white;
            text-align: center;
        }

        .contact-content {
            max-width: 900px;
            margin: 0 auto;
        }

        .contact-section .section-title {
            color: white;
        }

        .contact-section .section-title::after {
            background: white;
        }

        .contact-section .section-subtitle {
            color: rgba(255, 255, 255, 0.9);
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin: 60px 0;
        }

        .contact-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-item h3 {
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .contact-item p {
            font-size: 18px;
            margin: 10px 0;
        }

        .contact-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .contact-btn {
            background: #fff;
            color: #19448e;
            padding: 18px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
            background: #f8f9fa;
        }

        /* フッター */
        .footer {
            background: #1a237e;
            color: white;
            padding: 60px 0 30px;
            text-align: center;
        }

        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .footer h3 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #fff;
        }

        .footer p {
            margin: 8px 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* アニメーション */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* レスポンシブデザイン */
        @media (max-width: 768px) {
            /* ヘッダー調整 */
            .header-container {
                padding: 15px 20px;
            }

            /* ナビゲーション - モバイル用オーバーレイ */
            .nav {
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

            /* ハンバーガーメニューボタンを表示 */
            .menu-btn {
                display: flex;
            }

            /* レスポンシブ表示制御 */
            .sponly {
                display: block;
            }

            .pconly {
                display: none;
            }

            /* メニューが開いている時の body のスクロール制御 */
            body.menu-open {
                overflow: hidden;
            }

            .hero-content {
                flex-direction: column;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 32px;
            }

            .hero-text .subtitle {
                font-size: 18px;
            }

            .hero-company {
                writing-mode: horizontal-tb;
                text-orientation: unset;
                font-size: 28px;
                margin-left: 0;
                margin-top: 20px;
                letter-spacing: 4px;
                font-weight: 500;
            }

            .concept-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .section-title {
                font-size: 32px;
            }

            .contact-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo pconly">
                小久保植樹園
            </a>
            <nav class="nav" id="nav">
                <a href="/">ホーム</a>
                <a href="/works">施工実績</a>
                <a href="/company">会社案内</a>
                <a href="/recruit">採用情報</a>
                <a href="/contact">お問い合わせ</a>
            </nav>
            <button class="menu-btn" id="menuBtn">
                <span class="menu-line"></span>
                <span class="menu-line"></span>
                <span class="menu-line"></span>
            </button>
        </div>
    </header>

    <!-- メインビジュアル -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>緑豊かな空間づくり</h1>
                <p class="subtitle">信頼と技術で築く美しい庭園</p>
                <p class="description">伊勢市の植樹・造園専門業者として、お客様の想いを形にします。<br>四季を通じて美しい景観を演出する空間づくりを心がけています。</p>
                <a href="/contact" class="hero-btn">お問い合わせ</a>
            </div>
            <div class="hero-company">
                小久保植樹園
            </div>
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
            <h2 class="section-title">事業内容</h2>
            <p class="section-subtitle">植栽から造園、お手入れまで、緑に関するあらゆるご要望にお応えします</p>
            <div class="services-grid">
                <div class="service-card">
                    <span class="service-icon">🌱</span>
                    <h3>植栽・造園</h3>
                    <p>植木の植栽、芝生の施工、庭石・景石・灯篭の設置・撤去、緑化対策など、美しい緑空間の創造を行います。</p>
                </div>
                <div class="service-card">
                    <span class="service-icon">✂️</span>
                    <h3>お手入れ・管理</h3>
                    <p>植木の剪定（お手入れ）、庭木・生垣の刈込み、芝刈り（草刈り）、草取り、保養所等の年間管理を承ります。</p>
                </div>
                <div class="service-card">
                    <span class="service-icon">🛡️</span>
                    <h3>防除・特殊作業</h3>
                    <p>植木の消毒、防草対策（防草シート設置）、ハチの巣駆除、立木の伐採など専門的な作業に対応します。</p>
                </div>
                <div class="service-card">
                    <span class="service-icon">🚛</span>
                    <h3>施工・その他</h3>
                    <p>植木の移植、山砂・砂利の施工・運搬、駐車場の施工、遊具の設置、お墓の管理、門松の施工まで幅広く対応。</p>
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
        // ハンバーガーメニュー
        const menuBtn = document.getElementById(\'menuBtn\');
        const nav = document.getElementById(\'nav\');

        if (menuBtn && nav) {
            menuBtn.addEventListener(\'click\', function() {
                menuBtn.classList.toggle(\'is-active\');
                nav.classList.toggle(\'is-open\');
                document.body.classList.toggle(\'menu-open\');
            });

            // ナビゲーションリンクをクリックしたらメニューを閉じる
            nav.querySelectorAll(\'a\').forEach(link => {
                link.addEventListener(\'click\', function() {
                    menuBtn.classList.remove(\'is-active\');
                    nav.classList.remove(\'is-open\');
                    document.body.classList.remove(\'menu-open\');
                });
            });
        }

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