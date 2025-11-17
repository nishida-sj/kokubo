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

                    $html .= '</div></div>';
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