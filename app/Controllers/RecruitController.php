<?php

class RecruitController extends Controller
{
    public function index()
    {
        // データベースから採用情報設定を取得
        $db = Db::getInstance();
        $settingsData = $db->fetchAll("SELECT `key`, `value` FROM recruit_settings");
        $s = [];
        foreach ($settingsData as $row) {
            $s[$row['key']] = $row['value'];
        }

        // デフォルト値
        $pageTitle = h($s['page_title'] ?? '採用情報');
        $pageSubtitle = h($s['page_subtitle'] ?? '緑豊かな環境づくりを一緒に担う仲間を募集しています');

        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>採用情報 | 小久保植樹園</title>
    <meta name="description" content="小久保植樹園の採用情報。植樹・造園の仕事で一緒に働きませんか？未経験者歓迎、充実した研修制度でサポートします。">

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

        .job-positions {
            display: grid;
            gap: 40px;
        }

        .job-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #19448e;
        }

        .job-title {
            font-size: 24px;
            color: #19448e;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .detail-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #19448e;
            min-width: 120px;
            font-size: 16px;
        }

        .detail-value {
            flex: 1;
            font-size: 16px;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .benefit-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        .benefit-icon {
            font-size: 50px;
            margin-bottom: 15px;
            display: block;
        }

        .benefit-title {
            font-size: 18px;
            color: #19448e;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .benefit-description {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 30px;
        }

        .requirements-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #19448e;
            font-weight: bold;
            font-size: 18px;
        }

        .requirements-list li:last-child {
            border-bottom: none;
        }

        .cta-section {
            background: linear-gradient(135deg, #19448e 0%, #2c5aa0 100%);
            color: white;
            padding: 60px 40px;
            border-radius: 20px;
            text-align: center;
            margin-top: 60px;
        }

        .cta-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .cta-description {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .cta-button {
            display: inline-block;
            background: white;
            color: #19448e;
            padding: 18px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
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

            .job-details {
                grid-template-columns: 1fr;
            }

            .benefits-grid {
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
            <h1 class="page-title">' . $pageTitle . '</h1>
            <p class="page-subtitle">' . $pageSubtitle . '</p>
        </section>

        <div class="container">
            <!-- 募集職種 -->
            <section class="section">
                <h2 class="section-title">募集職種</h2>

                <div class="job-positions">';

        // 職種1
        if (($s['job1_enabled'] ?? '1') == '1') {
            $html .= '
                    <div class="job-card">
                        <h3 class="job-title">
                            <span>' . h($s['job1_icon'] ?? '🌳') . '</span>
                            ' . h($s['job1_title'] ?? '植栽・造園スタッフ') . '
                        </h3>
                        <p>' . h($s['job1_description'] ?? '') . '</p>

                        <div class="job-details">
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">雇用形態</div>
                                    <div class="detail-value">' . h($s['job1_employment_type'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">給与</div>
                                    <div class="detail-value">' . h($s['job1_salary'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">勤務時間</div>
                                    <div class="detail-value">' . h($s['job1_work_hours'] ?? '') . '</div>
                                </div>
                            </div>
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">休日</div>
                                    <div class="detail-value">' . h($s['job1_holidays'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">資格</div>
                                    <div class="detail-value">' . h($s['job1_qualifications'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">経験</div>
                                    <div class="detail-value">' . h($s['job1_experience'] ?? '') . '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
        }

        // 職種2
        if (($s['job2_enabled'] ?? '1') == '1') {
            $html .= '
                    <div class="job-card">
                        <h3 class="job-title">
                            <span>' . h($s['job2_icon'] ?? '✂️') . '</span>
                            ' . h($s['job2_title'] ?? '庭木管理スタッフ') . '
                        </h3>
                        <p>' . h($s['job2_description'] ?? '') . '</p>

                        <div class="job-details">
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">雇用形態</div>
                                    <div class="detail-value">' . h($s['job2_employment_type'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">給与</div>
                                    <div class="detail-value">' . h($s['job2_salary'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">勤務時間</div>
                                    <div class="detail-value">' . h($s['job2_work_hours'] ?? '') . '</div>
                                </div>
                            </div>
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">休日</div>
                                    <div class="detail-value">' . h($s['job2_holidays'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">資格</div>
                                    <div class="detail-value">' . h($s['job2_qualifications'] ?? '') . '</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">経験</div>
                                    <div class="detail-value">' . h($s['job2_experience'] ?? '') . '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
        }

        $html .= '
                </div>
            </section>

            <!-- 福利厚生 -->
            <section class="section">
                <h2 class="section-title">福利厚生</h2>
                <div class="section-content">
                    <div class="benefits-grid">';

        // 福利厚生を動的に生成
        $benefits = $s['benefits'] ?? '';
        $benefitLines = array_filter(explode("\n", $benefits));
        foreach ($benefitLines as $line) {
            $parts = explode('|', $line, 2);
            if (count($parts) == 2) {
                $html .= '
                        <div class="benefit-card">
                            <h3 class="benefit-title">' . h(trim($parts[0])) . '</h3>
                            <p class="benefit-description">' . h(trim($parts[1])) . '</p>
                        </div>';
            }
        }

        $html .= '
                    </div>
                </div>
            </section>

            <!-- 求める人物像 -->
            <section class="section">
                <h2 class="section-title">求める人物像</h2>
                <div class="section-content">
                    <ul class="requirements-list">';

        // 求める人物像を動的に生成
        $requirements = $s['requirements'] ?? '';
        $requirementLines = array_filter(explode("\n", $requirements));
        foreach ($requirementLines as $line) {
            $parts = explode('|', $line, 2);
            if (count($parts) == 2) {
                $html .= '
                        <li><strong>' . h(trim($parts[0])) . '</strong><br>' . h(trim($parts[1])) . '</li>';
            }
        }

        $html .= '
                    </ul>
                </div>
            </section>

            <!-- 応募について -->
            <div class="cta-section">
                <h2 class="cta-title">' . h($s['cta_title'] ?? '一緒に働きませんか？') . '</h2>
                <p class="cta-description">' . nl2br(h($s['cta_description'] ?? '')) . '</p>
                <a href="' . h($s['cta_button_url'] ?? '/contact') . '" class="cta-button">' . h($s['cta_button_text'] ?? '採用に関するお問い合わせ') . '</a>
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