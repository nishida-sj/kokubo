<?php

class WorksController extends Controller
{
    public function index()
    {
        try {
            // 設定値を取得
            $companyName = h(setting('company_name', '小久保植樹園'));
            $companyTel = h(setting('company_tel', '0596-00-0000'));
            $companyPostalCode = h(setting('company_postal_code', '516-0000'));
            $companyAddress = h(setting('company_address', '三重県伊勢市'));

            // データベースから実績を取得
            $db = Database::getInstance();
            $works = $db->fetchAll("
                SELECT w.*, c.name as category_name
                FROM works w
                LEFT JOIN categories c ON w.category_id = c.id
                WHERE w.is_published = 1
                ORDER BY w.created_at DESC
            ");

            // すべてのタグを取得
            $tags = $db->fetchAll("SELECT * FROM tags ORDER BY name ASC");

            // 各実績に紐づくタグを取得
            $workTags = [];
            foreach ($works as &$work) {
                $workTagsData = $db->fetchAll("
                    SELECT t.id, t.name
                    FROM work_tags wt
                    JOIN tags t ON wt.tag_id = t.id
                    WHERE wt.work_id = ?
                    ORDER BY t.name ASC
                ", [$work['id']]);

                $work['tags'] = $workTagsData;

                // タグIDの配列も保持（フィルタリング用）
                $work['tag_ids'] = array_map(function($tag) {
                    return $tag['id'];
                }, $workTagsData);
            }
            unset($work);

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

        .works-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        @media (min-width: 768px) {
            .works-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 35px;
            }
        }

        @media (min-width: 1024px) {
            .works-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 40px;
            }
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
            background: #f0f0f0;
            position: relative;
            overflow: hidden;
        }

        .work-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .work-card:hover .work-image img {
            transform: scale(1.05);
        }

        .work-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #19448e 0%, #4a90e2 100%);
            color: white;
            font-size: 60px;
        }

        .work-category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(25, 68, 142, 0.9);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            z-index: 1;
        }

        .work-content {
            padding: 30px;
        }

        .work-title {
            font-size: 20px;
            color: #19448e;
            margin-bottom: 16px;
            font-weight: 600;
            line-height: 1.4;
        }

        .work-description {
            color: #555;
            line-height: 1.7;
            font-size: 15px;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .work-location {
            color: #888;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* タグフィルター */
        .tag-filters {
            margin-bottom: 40px;
            text-align: center;
        }

        .tag-filters-title {
            font-size: 20px;
            color: #19448e;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .tag-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }

        .tag-button {
            background: #fff;
            color: #19448e;
            border: 2px solid #19448e;
            padding: 10px 24px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            outline: none;
        }

        .tag-button:hover {
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        .tag-button.active {
            background: #19448e;
            color: white;
        }

        .work-card[data-hidden="true"] {
            display: none;
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
            <h1 class="page-title">施工実績</h1>
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
                // タグフィルターボタン
                if (!empty($tags)) {
                    $html .= '
                    <div class="tag-filters">
                        <h3 class="tag-filters-title">🏷️ タグで絞り込む</h3>
                        <div class="tag-buttons">
                            <button class="tag-button active" data-tag-id="all">すべて</button>';

                    foreach ($tags as $tag) {
                        $html .= '<button class="tag-button" data-tag-id="' . h($tag['id']) . '">' . h($tag['name']) . '</button>';
                    }

                    $html .= '
                        </div>
                    </div>';
                }

                $html .= '<div class="works-grid">';
                foreach ($works as $work) {
                    // 画像パスの自動修正（旧形式のパスに/uploadsを追加）
                    $imagePath = $work['main_image'];
                    if ($imagePath && strpos($imagePath, '/uploads/') === false && strpos($imagePath, '/') === 0) {
                        $imagePath = '/uploads' . $imagePath;
                    }

                    // カテゴリー別のアイコンを設定（画像がない場合のフォールバック）
                    $icon = '🌳';
                    if (strpos($work['category_name'], '植栽') !== false) $icon = '🌱';
                    if (strpos($work['category_name'], '剪定') !== false) $icon = '✂️';
                    if (strpos($work['category_name'], '造園') !== false) $icon = '🏡';
                    if (strpos($work['category_name'], '管理') !== false) $icon = '🌿';

                    // タグIDをJSON形式で属性に追加（フィルタリング用）
                    $tagIdsJson = json_encode($work['tag_ids']);

                    $html .= '
                    <a href="/works/' . h($work['slug']) . '" style="text-decoration: none; color: inherit;">
                        <div class="work-card" data-tag-ids=\'' . h($tagIdsJson) . '\'>
                            <div class="work-image">';

                    if ($imagePath) {
                        $fullImageUrl = 'https://kokubosyokuju.geo.jp' . $imagePath;
                        $html .= '<img src="' . h($fullImageUrl) . '" alt="' . h($work['title']) . '" loading="lazy">';
                    } else {
                        $html .= '<div class="work-image-placeholder">' . $icon . '</div>';
                    }

                    $html .= '<div class="work-category-badge">📋 ' . h($work['category_name']) . '</div>
                        </div>
                        <div class="work-content">
                            <div class="work-title">' . h($work['title']) . '</div>
                            <div class="work-description">' . h($work['description']) . '</div>';

                    if ($work['location']) {
                        $html .= '<div class="work-location">📍 ' . h($work['location']) . '</div>';
                    }

                    $html .= '</div></div>
                    </a>';
                }
                $html .= '</div>';
            }

            $html .= '
        </div>
    </div>

    <!-- タグフィルタリングのJavaScript -->
    <script>
        document.addEventListener(\'DOMContentLoaded\', function() {
            const tagButtons = document.querySelectorAll(\'.tag-button\');
            const workCards = document.querySelectorAll(\'.work-card\');

            tagButtons.forEach(button => {
                button.addEventListener(\'click\', function() {
                    const selectedTagId = this.getAttribute(\'data-tag-id\');

                    // すべてのボタンからactiveクラスを削除
                    tagButtons.forEach(btn => btn.classList.remove(\'active\'));

                    // クリックされたボタンにactiveクラスを追加
                    this.classList.add(\'active\');

                    // 実績カードをフィルタリング
                    workCards.forEach(card => {
                        if (selectedTagId === \'all\') {
                            // 「すべて」が選択された場合はすべて表示
                            card.removeAttribute(\'data-hidden\');
                        } else {
                            // 特定のタグが選択された場合
                            const cardTagIds = JSON.parse(card.getAttribute(\'data-tag-ids\') || \'[]\');

                            if (cardTagIds.includes(parseInt(selectedTagId))) {
                                // タグを持っている実績を表示
                                card.removeAttribute(\'data-hidden\');
                            } else {
                                // タグを持っていない実績を非表示
                                card.setAttribute(\'data-hidden\', \'true\');
                            }
                        }
                    });
                });
            });
        });
    </script>

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

        } catch (Exception $e) {
            return '<h1>Works Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p><p><a href="/">ホームに戻る</a></p>';
        }
    }

    public function show($slug)
    {
        try {
            // 設定値を取得
            $companyName = h(setting('company_name', '小久保植樹園'));
            $companyTel = h(setting('company_tel', '0596-00-0000'));
            $companyPostalCode = h(setting('company_postal_code', '516-0000'));
            $companyAddress = h(setting('company_address', '三重県伊勢市'));

            // データベースから実績を取得
            $db = Database::getInstance();
            $work = $db->fetch("
                SELECT w.*, c.name as category_name
                FROM works w
                LEFT JOIN categories c ON w.category_id = c.id
                WHERE w.slug = ? AND w.is_published = 1
                LIMIT 1
            ", [$slug]);

            if (!$work) {
                return '<h1>実績が見つかりません</h1><p><a href="/works">実績一覧に戻る</a></p>';
            }

            // タグを取得
            $tags = $db->fetchAll("
                SELECT t.id, t.name
                FROM work_tags wt
                JOIN tags t ON wt.tag_id = t.id
                WHERE wt.work_id = ?
                ORDER BY t.name ASC
            ", [$work['id']]);

            // 追加画像を取得
            $additionalImages = $db->fetchAll("
                SELECT *
                FROM work_images
                WHERE work_id = ?
                ORDER BY sort_order ASC
            ", [$work['id']]);

            // 画像パスの自動修正
            $mainImagePath = $work['main_image'];
            if ($mainImagePath && strpos($mainImagePath, '/uploads/') === false && strpos($mainImagePath, '/') === 0) {
                $mainImagePath = '/uploads' . $mainImagePath;
            }

            $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . h($work['title']) . ' | 施工実績 | 小久保植樹園</title>
    <meta name="description" content="' . h(mb_substr($work['description'], 0, 150)) . '">

    <!-- フォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Noto Serif JP", "Hiragino Mincho ProN", "Yu Mincho", "游明朝", serif;
            line-height: 1.8;
            color: #333;
            background: #e8f5e9;
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
        }

        /* メイン画像 */
        .hero-image {
            width: 100%;
            height: 60vh;
            min-height: 400px;
            max-height: 600px;
            position: relative;
            overflow: hidden;
            background: #e0e0e0;
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #19448e 0%, #4a90e2 100%);
            color: white;
            font-size: 120px;
        }

        .breadcrumb {
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .breadcrumb-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb a {
            color: #19448e;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .work-header {
            margin-bottom: 40px;
        }

        .work-title {
            font-size: 36px;
            color: #19448e;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.4;
        }

        .work-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .meta-label {
            font-size: 14px;
            color: #888;
            font-weight: 500;
        }

        .meta-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .category-badge {
            display: inline-block;
            background: #19448e;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 500;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tag {
            background: #e3f2fd;
            color: #19448e;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .work-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 60px;
            margin-bottom: 60px;
        }

        .work-description {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            line-height: 2;
            font-size: 16px;
            white-space: pre-wrap;
        }

        .work-sidebar {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: fit-content;
        }

        .sidebar-section {
            margin-bottom: 30px;
        }

        .sidebar-section:last-child {
            margin-bottom: 0;
        }

        .sidebar-title {
            font-size: 18px;
            color: #19448e;
            margin-bottom: 15px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        .sidebar-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .sidebar-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }

        .gallery {
            margin-top: 60px;
        }

        .gallery-title {
            font-size: 28px;
            color: #19448e;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            aspect-ratio: 4/3;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #19448e;
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-top: 40px;
        }

        .back-button:hover {
            background: #2c5aa0;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25, 68, 142, 0.3);
        }

        /* フッター */
        .footer {
            background: #1a237e;
            color: white;
            padding: 40px 0;
            text-align: center;
            margin-top: 80px;
        }

        .footer p {
            margin: 8px 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* レスポンシブ */
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

            .work-content {
                grid-template-columns: 1fr;
            }

            .work-sidebar {
                order: -1;
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

            .work-title {
                font-size: 28px;
            }

            .work-meta {
                gap: 20px;
            }

            .hero-image {
                height: 50vh;
                min-height: 300px;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
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
        <div class="container">
            <!-- パンくずリスト -->
            <div style="margin-top: 20px; margin-bottom: 15px;">
                <div style="font-size: 14px; color: #666;">
                    <a href="/" style="color: #19448e; text-decoration: none;">ホーム</a> /
                    <a href="/works" style="color: #19448e; text-decoration: none;">施工実績</a> /
                    <span style="color: #333;">' . h($work['title']) . '</span>
                </div>
            </div>

            <!-- タイトル -->
            <div class="work-header" style="margin-bottom: 30px; text-align: center;">
                <h1 class="work-title">' . h($work['title']) . '</h1>
            </div>

            <!-- メイン画像 -->
            <div class="hero-image" style="margin-bottom: 20px;">';

            if ($mainImagePath) {
                $fullImageUrl = 'https://kokubosyokuju.geo.jp' . $mainImagePath;
                $html .= '<img id="main-work-image" src="' . h($fullImageUrl) . '" alt="' . h($work['title']) . '" style="width: 100%; height: auto; display: block; border-radius: 8px; transition: opacity 0.3s ease;">';
            } else {
                $icon = '🌳';
                if (strpos($work['category_name'], '植栽') !== false) $icon = '🌱';
                if (strpos($work['category_name'], '剪定') !== false) $icon = '✂️';
                if (strpos($work['category_name'], '造園') !== false) $icon = '🏡';
                if (strpos($work['category_name'], '管理') !== false) $icon = '🌿';

                $html .= '<div id="main-work-image" class="hero-image-placeholder" style="width: 100%; height: 400px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 80px; border-radius: 8px;">' . $icon . '</div>';
            }

            $html .= '
            </div>

            <!-- サムネイルギャラリー（追加画像） -->';

            if (!empty($additionalImages)) {
                $html .= '
            <div class="thumbnail-gallery" style="display: flex; gap: 10px; margin-bottom: 40px; overflow-x: auto; padding: 5px 0;">';

                // メイン画像もサムネイルに追加
                if ($mainImagePath) {
                    $fullImageUrl = 'https://kokubosyokuju.geo.jp' . $mainImagePath;
                    $html .= '
                <div class="thumbnail-item" style="flex: 0 0 auto; width: 150px; height: 100px; position: relative;">
                    <img src="' . h($fullImageUrl) . '"
                         alt="' . h($work['title']) . '"
                         class="thumbnail-img active"
                         onclick="changeMainImage(this.src)"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px; cursor: pointer; border: 3px solid #19448e; transition: all 0.3s ease;">
                </div>';
                }

                foreach ($additionalImages as $img) {
                    $imgPath = $img['path'];
                    if ($imgPath && strpos($imgPath, '/uploads/') === false && strpos($imgPath, '/') === 0) {
                        $imgPath = '/uploads' . $imgPath;
                    }

                    if ($imgPath) {
                        $imgUrl = 'https://kokubosyokuju.geo.jp' . $imgPath;
                        $html .= '
                <div class="thumbnail-item" style="flex: 0 0 auto; width: 150px; height: 100px; position: relative;">
                    <img src="' . h($imgUrl) . '"
                         alt="' . h($work['title']) . '"
                         class="thumbnail-img"
                         onclick="changeMainImage(this.src)"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px; cursor: pointer; border: 3px solid transparent; transition: all 0.3s ease;">
                </div>';
                    }
                }

                $html .= '
            </div>';
            }

            $html .= '
            <!-- メタ情報とコンテンツ -->
            <div style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 40px;">
                <div style="display: flex; gap: 30px; margin-bottom: 30px; flex-wrap: wrap; border-bottom: 1px solid #e0e0e0; padding-bottom: 20px;">
                    <div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">カテゴリ</div>
                        <div style="font-size: 16px; font-weight: 500;">📋 ' . h($work['category_name']) . '</div>
                    </div>';

            if ($work['location']) {
                $html .= '
                    <div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">施工場所</div>
                        <div style="font-size: 16px; font-weight: 500;">📍 ' . h($work['location']) . '</div>
                    </div>';
            }

            if (!empty($work['construction_period'])) {
                $html .= '
                    <div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">工期</div>
                        <div style="font-size: 16px; font-weight: 500;">📅 ' . h($work['construction_period']) . '</div>
                    </div>';
            }

            $html .= '
                    <div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">登録日</div>
                        <div style="font-size: 16px; font-weight: 500;">' . h(date('Y年', strtotime($work['created_at']))) . '</div>
                    </div>
                </div>';

            if (!empty($tags)) {
                $html .= '
                <div style="margin-bottom: 30px;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 10px;">タグ</div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">';
                foreach ($tags as $tag) {
                    $html .= '<span style="background: #e3f2fd; color: #1976d2; padding: 5px 15px; border-radius: 15px; font-size: 14px;">🏷️ ' . h($tag['name']) . '</span>';
                }
                $html .= '
                    </div>
                </div>';
            }

            $html .= '
                <div>
                    <div style="line-height: 2; color: #333; font-size: 15px;">
                        ' . nl2br(h($work['description'])) . '
                    </div>
                </div>
            </div>';

            $html .= '
            <div style="text-align: center;">
                <a href="/works" class="back-button">← 施工実績一覧に戻る</a>
            </div>
        </div>
    </div>

    <!-- 画像切り替えスクリプト -->
    <script>
    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById(\'main-work-image\');
        const thumbnails = document.querySelectorAll(\'.thumbnail-img\');

        // メイン画像を切り替え（フェード効果）
        mainImage.style.opacity = \'0\';
        setTimeout(function() {
            mainImage.src = imageSrc;
            mainImage.style.opacity = \'1\';
        }, 300);

        // サムネイルのアクティブ状態を切り替え
        thumbnails.forEach(function(thumb) {
            if (thumb.src === imageSrc) {
                thumb.classList.add(\'active\');
                thumb.style.borderColor = \'#19448e\';
            } else {
                thumb.classList.remove(\'active\');
                thumb.style.borderColor = \'transparent\';
            }
        });
    }

    // サムネイルのホバー効果
    document.addEventListener(\'DOMContentLoaded\', function() {
        const thumbnails = document.querySelectorAll(\'.thumbnail-img\');
        thumbnails.forEach(function(thumb) {
            thumb.addEventListener(\'mouseenter\', function() {
                if (!this.classList.contains(\'active\')) {
                    this.style.borderColor = \'#4a90e2\';
                }
            });
            thumb.addEventListener(\'mouseleave\', function() {
                if (!this.classList.contains(\'active\')) {
                    this.style.borderColor = \'transparent\';
                }
            });
        });
    });
    </script>

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

        } catch (Exception $e) {
            return '<h1>Works Detail Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p><p><a href="/works">実績一覧に戻る</a></p>';
        }
    }
}