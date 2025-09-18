<?php

class RecruitController extends Controller
{
    public function index()
    {
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
            <h1 class="page-title">🌱 採用情報</h1>
            <p class="page-subtitle">緑豊かな環境づくりを一緒に担う仲間を募集しています</p>
        </section>

        <div class="container">
            <!-- 募集職種 -->
            <section class="section">
                <h2 class="section-title">募集職種</h2>

                <div class="job-positions">
                    <div class="job-card">
                        <h3 class="job-title">
                            <span>🌳</span>
                            植栽・造園スタッフ
                        </h3>
                        <p>植栽工事や庭園の設計・施工を担当していただきます。未経験の方も先輩スタッフが丁寧に指導いたします。</p>

                        <div class="job-details">
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">雇用形態</div>
                                    <div class="detail-value">正社員</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">給与</div>
                                    <div class="detail-value">月給 22万円〜35万円（経験・能力による）</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">勤務時間</div>
                                    <div class="detail-value">8:00〜17:00（休憩1時間）</div>
                                </div>
                            </div>
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">休日</div>
                                    <div class="detail-value">日曜・祝日・年末年始</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">資格</div>
                                    <div class="detail-value">普通自動車免許（必須）</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">経験</div>
                                    <div class="detail-value">未経験者歓迎</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="job-card">
                        <h3 class="job-title">
                            <span>✂️</span>
                            庭木管理スタッフ
                        </h3>
                        <p>個人宅や企業施設の庭木剪定・管理業務を担当していただきます。技術をしっかりと身につけることができます。</p>

                        <div class="job-details">
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">雇用形態</div>
                                    <div class="detail-value">正社員・パート</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">給与</div>
                                    <div class="detail-value">時給 1,200円〜1,800円</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">勤務時間</div>
                                    <div class="detail-value">8:00〜17:00（時間相談可）</div>
                                </div>
                            </div>
                            <div>
                                <div class="detail-item">
                                    <div class="detail-label">休日</div>
                                    <div class="detail-value">日曜・祝日（シフト制）</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">資格</div>
                                    <div class="detail-value">普通自動車免許（必須）</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">経験</div>
                                    <div class="detail-value">経験者優遇・未経験者歓迎</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 福利厚生 -->
            <section class="section">
                <h2 class="section-title">福利厚生</h2>
                <div class="section-content">
                    <div class="benefits-grid">
                        <div class="benefit-card">
                            <span class="benefit-icon">🏥</span>
                            <h3 class="benefit-title">社会保険完備</h3>
                            <p class="benefit-description">健康保険・厚生年金・雇用保険・労災保険に加入</p>
                        </div>
                        <div class="benefit-card">
                            <span class="benefit-icon">📚</span>
                            <h3 class="benefit-title">研修制度</h3>
                            <p class="benefit-description">先輩スタッフによる丁寧な技術指導と外部研修参加支援</p>
                        </div>
                        <div class="benefit-card">
                            <span class="benefit-icon">🚗</span>
                            <h3 class="benefit-title">交通費支給</h3>
                            <p class="benefit-description">通勤手当・現場への交通費を全額支給</p>
                        </div>
                        <div class="benefit-card">
                            <span class="benefit-icon">🏆</span>
                            <h3 class="benefit-title">資格取得支援</h3>
                            <p class="benefit-description">造園技能士等の資格取得費用を会社が負担</p>
                        </div>
                        <div class="benefit-card">
                            <span class="benefit-icon">👨‍👩‍👧‍👦</span>
                            <h3 class="benefit-title">家族手当</h3>
                            <p class="benefit-description">扶養家族がいる場合の手当支給</p>
                        </div>
                        <div class="benefit-card">
                            <span class="benefit-icon">🎉</span>
                            <h3 class="benefit-title">各種手当</h3>
                            <p class="benefit-description">皆勤手当・賞与年2回・昇給年1回</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 求める人物像 -->
            <section class="section">
                <h2 class="section-title">求める人物像</h2>
                <div class="section-content">
                    <ul class="requirements-list">
                        <li><strong>自然や植物が好きな方</strong><br>緑に囲まれた環境で働きたい方を歓迎します</li>
                        <li><strong>体力に自信がある方</strong><br>屋外での作業が中心となります</li>
                        <li><strong>責任感のある方</strong><br>お客様の大切な庭木を扱う責任ある仕事です</li>
                        <li><strong>チームワークを大切にする方</strong><br>スタッフ同士で協力して作業を進めます</li>
                        <li><strong>向上心のある方</strong><br>技術習得に積極的に取り組める方</li>
                        <li><strong>地域に貢献したい方</strong><br>伊勢の美しい環境づくりに参加しませんか</li>
                    </ul>
                </div>
            </section>

            <!-- 応募について -->
            <div class="cta-section">
                <h2 class="cta-title">🌿 一緒に働きませんか？</h2>
                <p class="cta-description">
                    緑豊かな環境づくりを通じて、地域社会に貢献する仕事です。<br>
                    未経験の方も歓迎します。まずはお気軽にお問い合わせください。
                </p>
                <a href="/contact" class="cta-button">採用に関するお問い合わせ</a>
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
    }
}