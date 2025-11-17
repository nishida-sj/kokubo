<?php

class ContactController extends Controller
{
    public function index()
    {
        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ | 小久保植樹園</title>

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
            background: rgba(80, 80, 80, 0.4);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .nav a:hover {
            color: #ccc;
        }

        /* メインコンテンツ */
        .main-content {
            margin-top: 100px;
            min-height: 100vh;
            padding: 60px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* ページヘッダー */
        .page-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .page-title {
            font-size: 42px;
            color: #19448e;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .page-subtitle {
            font-size: 16px;
            color: #666;
        }

        /* お問い合わせ情報カード */
        .contact-info-cards {
            max-width: 400px;
            margin: 0 auto 60px auto;
        }

        .info-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .info-card-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .info-card-title {
            font-size: 20px;
            color: #19448e;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .info-card-content {
            color: #666;
            font-size: 15px;
            line-height: 1.8;
        }

        .info-card-highlight {
            color: #2E7D32;
            font-size: 24px;
            font-weight: 600;
            margin: 10px 0;
        }

        /* フォームカード */
        .form-card {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-size: 28px;
            color: #19448e;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 15px;
        }

        .required {
            color: #e53935;
            margin-left: 4px;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #19448e;
            box-shadow: 0 0 0 3px rgba(25, 68, 142, 0.1);
        }

        .form-textarea {
            height: 180px;
            resize: vertical;
        }

        .form-submit {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 16px 50px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
        }

        /* フッター */
        .footer {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            padding: 60px 20px 30px;
            text-align: center;
            margin-top: 80px;
        }

        .footer h3 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .footer p {
            margin: 8px 0;
            font-size: 16px;
            opacity: 0.9;
        }

        /* レスポンシブ */
        @media (max-width: 768px) {
            .header-container {
                padding: 15px 20px;
            }

            .logo {
                font-size: 24px;
            }

            .nav {
                display: none;
            }

            .page-title {
                font-size: 32px;
            }

            .form-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo">小久保植樹園</a>
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
        <div class="container">
            <!-- ページヘッダー -->
            <div class="page-header">
                <h1 class="page-title">📞 お問い合わせ</h1>
                <p class="page-subtitle">お気軽にご相談ください。専門スタッフが丁寧に対応いたします。</p>
            </div>

            <!-- お問い合わせ情報カード -->
            <div class="contact-info-cards">
                <div class="info-card">
                    <div class="info-card-icon">☎️</div>
                    <h3 class="info-card-title">お電話でのお問い合わせ</h3>
                    <div class="info-card-highlight">0596-00-0000</div>
                    <div class="info-card-content">
                        営業時間：平日 8:00-18:00<br>
                        土曜 8:00-17:00<br>
                        定休日：日曜・祝日
                    </div>
                </div>
            </div>

            <!-- お問い合わせフォーム -->
            <div class="form-card">
                <h2 class="form-title">📝 お問い合わせフォーム</h2>
                <form method="POST" action="/contact/send">
                    <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

                    <div class="form-group">
                        <label for="name" class="form-label">お名前（法人の方はお名前と会社名）<span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-input" required placeholder="山田 太郎">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">メールアドレス<span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-input" required placeholder="example@email.com">
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">電話番号</label>
                        <input type="tel" id="phone" name="phone" class="form-input" placeholder="090-1234-5678">
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">件名</label>
                        <input type="text" id="subject" name="subject" class="form-input" placeholder="例：庭園設計について">
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">お問い合わせ内容<span class="required">*</span></label>
                        <textarea id="message" name="message" class="form-textarea" required placeholder="ご質問やご要望をお聞かせください"></textarea>
                    </div>

                    <button type="submit" class="form-submit">送信する</button>
                </form>
            </div>
        </div>
    </div>

    <!-- フッター -->
    <footer class="footer">
        <div style="max-width: 800px; margin: 0 auto;">
            <h3>小久保植樹園</h3>
            <p>〒516-0000 三重県伊勢市</p>
            <p>TEL: 0596-00-0000</p>
            <p style="margin-top: 20px; opacity: 0.8;">© 2024 小久保植樹園. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>';

        return $html;
    }

    public function send()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->index();
            }

            // CSRFトークン確認
            if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                throw new Exception('不正なリクエストです。');
            }

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'] ?? '';

            if (empty($name) || empty($email) || empty($message)) {
                throw new Exception('必須項目を入力してください。');
            }

            // データベースに保存
            $db = Db::getInstance();
            $db->insert('contacts', [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);

            return '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了 | 小久保植樹園</title>

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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            max-width: 600px;
            width: 100%;
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
            text-align: center;
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        h1 {
            color: #2E7D32;
            font-size: 32px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .success-message {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            color: #1B5E20;
        }

        .success-message p {
            font-size: 16px;
            margin: 10px 0;
            line-height: 1.8;
        }

        .btn {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 16px 50px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
        }

        @media (max-width: 768px) {
            .success-container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 28px;
            }

            .success-icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1>送信完了</h1>
        <div class="success-message">
            <p><strong>お問い合わせいただき、誠にありがとうございます。</strong></p>
            <p>内容を確認後、2営業日以内にご返信いたします。</p>
            <p>今しばらくお待ちくださいませ。</p>
        </div>
        <a href="/" class="btn">ホームに戻る</a>
    </div>
</body>
</html>';

        } catch (Exception $e) {
            return '<h1>送信エラー</h1><p>' . htmlspecialchars($e->getMessage()) . '</p><p><a href="/contact">戻る</a></p>';
        }
    }
}