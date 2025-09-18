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
    <meta name="description" content="小久保植樹園の施工実績をご紹介。植栽工事・造園・庭木の手入れなど、これまでの実績をご覧ください。">

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

        .works-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-top: 40px;
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
            font-size: 60px;
            position: relative;
            overflow: hidden;
        }

        .work-content {
            padding: 30px;
        }

        .work-title {
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
            padding: 4px 12px;
            background: #f0f0f0;
            border-radius: 12px;
            display: inline-block;
        }

        .work-description {
            color: #555;
            line-height: 1.6;
            font-size: 15px;
            margin-bottom: 16px;
        }

        .work-location {
            color: #888;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stats-section {
            margin-top: 60px;
            padding: 40px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 20px;
            text-align: center;
        }

        .stats-title {
            font-size: 24px;
            color: #19448e;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #19448e;
            display: block;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
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
            .nav {
                display: none;
            }

            .page-title {
                font-size: 32px;
            }

            .works-grid {
                grid-template-columns: 1fr;
                gap: 30px;
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
            <h1 class="page-title">🌿 施工実績</h1>
            <p class="page-subtitle">これまでに手がけた植栽・造園工事の実績をご紹介いたします</p>
        </section>

        <div class="container">';

            if (empty($works)) {
                $html .= '<div style="text-align: center; padding: 60px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 60px; margin-bottom: 20px;">🌿</div>
                    <h3 style="color: #19448e; margin-bottom: 15px;">実績データを準備中です</h3>
                    <p style="color: #666;">これまでの施工実績を整理中です。しばらくお待ちください。</p>
                </div>';
            } else {
                $html .= '<div class="works-grid">';
                foreach ($works as $work) {
                    // カテゴリー別のアイコンを設定
                    $icon = '🌳';
                    if (strpos($work['category_name'], '植栽') !== false) $icon = '🌱';
                    if (strpos($work['category_name'], '剪定') !== false) $icon = '✂️';
                    if (strpos($work['category_name'], '造園') !== false) $icon = '🏡';
                    if (strpos($work['category_name'], '管理') !== false) $icon = '🌿';

                    $html .= '
                    <div class="work-card">
                        <div class="work-image">' . $icon . '</div>
                        <div class="work-content">
                            <div class="work-title">' . h($work['title']) . '</div>
                            <div class="work-category">📋 ' . h($work['category_name']) . '</div>
                            <div class="work-description">' . h($work['description']) . '</div>';

                    if ($work['location']) {
                        $html .= '<div class="work-location">📍 ' . h($work['location']) . '</div>';
                    }

                    $html .= '</div></div>';
                }
                $html .= '</div>';
            }

            $html .= '
            <!-- 実績統計セクション -->
            <div class="stats-section">
                <h3 class="stats-title">📊 実績統計</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">' . count($works) . '</span>
                        <div class="stat-label">登録実績数</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <div class="stat-label">お客様満足度</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">40</span>
                        <div class="stat-label">年の実績</div>
                    </div>
                </div>
            </div>
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

        } catch (Exception $e) {
            return '<h1>Works Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p><p><a href="/">ホームに戻る</a></p>';
        }
    }

    public function show($slug)
    {
        return '<h1>実績詳細: ' . htmlspecialchars($slug) . '</h1><p><a href="/works">実績一覧に戻る</a></p>';
    }
}